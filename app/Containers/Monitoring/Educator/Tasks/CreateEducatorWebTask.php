<?php

namespace App\Containers\Monitoring\Educator\Tasks;

use App\Containers\AppSection\Authorization\Enums\Role as RoleEnum;
use App\Containers\AppSection\Authorization\Models\Role as RoleModel;
use App\Containers\AppSection\User\Models\User;
use App\Containers\AppSection\User\Mails\EducatorUserCreatedEmail;
use App\Containers\AppSection\User\Tasks\CreateUserTask;
use App\Containers\Monitoring\Educator\Data\Repositories\EducatorRepository;
use App\Containers\Monitoring\Educator\Events\EducatorCreated;
use App\Containers\Monitoring\Educator\Models\Educator;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

final class CreateEducatorWebTask extends ParentTask
{
    public function __construct(
        private readonly EducatorRepository $repository,
        private readonly CreateUserTask $createUserTask,
    ) {
    }

    public function run(array $data): array
    {
        return DB::transaction(function () use ($data) {
            // Extract childcare center IDs
            $childcareCenterIds = $data['childcare_center_ids'] ?? [];
            unset($data['childcare_center_ids']);

            // Generate random password
            $password = Str::random(12);

            // Create user account
            $user = $this->createUserTask->run([
                'name' => ($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? ''),
                'email' => $data['email'],
                'password' => $password,
            ]);

            // Assign educator role to user
            $roleEducator = RoleModel::findByName(RoleEnum::EDUCATOR->value, 'api');
            if ($roleEducator) {
                $user->assignRole($roleEducator);
            }

            // Add user_id to educator data
            $data['user_id'] = $user->id;
            unset($data['email']); // Remove email from educator data

            // Convert birth date from d/m/Y to Y-m-d
            if (isset($data['birth']) && !empty($data['birth'])) {
                try {
                    $date = \DateTime::createFromFormat('d/m/Y', $data['birth']);
                    if ($date) {
                        $data['birth'] = $date->format('Y-m-d');
                    }
                } catch (\Exception $e) {
                    $data['birth'] = date('Y-m-d', strtotime($data['birth']));
                }
            }

            // Create educator
            $educator = $this->repository->create($data);

            // Assign childcare centers (at least one required)
            if (!empty($childcareCenterIds)) {
                $syncData = [];
                $now = now();
                foreach ($childcareCenterIds as $centerId) {
                    $syncData[$centerId] = ['assigned_at' => $now];
                }
                $educator->childcareCenters()->sync($syncData);
            }

            // Send email to educator
            Mail::send(new EducatorUserCreatedEmail($user, $educator, $password));

            return [
                'educator' => $educator,
                'password' => $password, // Return password to show to admin
            ];
        });
    }
}


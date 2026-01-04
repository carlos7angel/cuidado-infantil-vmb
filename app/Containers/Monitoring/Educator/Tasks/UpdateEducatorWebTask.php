<?php

namespace App\Containers\Monitoring\Educator\Tasks;

use App\Containers\Monitoring\Educator\Data\Repositories\EducatorRepository;
use App\Containers\Monitoring\Educator\Events\EducatorUpdated;
use App\Containers\Monitoring\Educator\Models\Educator;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Support\Facades\DB;

final class UpdateEducatorWebTask extends ParentTask
{
    public function __construct(
        private readonly EducatorRepository $repository,
    ) {
    }

    public function run(array $data, $id): Educator
    {
        return DB::transaction(function () use ($data, $id) {
            // Extract childcare center IDs
            $childcareCenterIds = $data['childcare_center_ids'] ?? null;
            unset($data['childcare_center_ids']);

            // Remove email if present (shouldn't update user email here)
            unset($data['email']);

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

            // Update educator
            $educator = $this->repository->update($data, $id);

            // Update childcare center assignments if provided
            if ($childcareCenterIds !== null) {
                $syncData = [];
                $now = now();
                foreach ($childcareCenterIds as $centerId) {
                    $syncData[$centerId] = ['assigned_at' => $now];
                }
                $educator->childcareCenters()->sync($syncData);
            }

            EducatorUpdated::dispatch($educator);

            return $educator;
        });
    }
}


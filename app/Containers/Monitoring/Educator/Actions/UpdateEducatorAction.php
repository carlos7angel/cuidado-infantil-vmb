<?php

namespace App\Containers\Monitoring\Educator\Actions;

use App\Containers\AppSection\User\Tasks\UpdateUserTask;
use App\Containers\Monitoring\Educator\Data\Repositories\EducatorRepository;
use App\Containers\Monitoring\Educator\Models\Educator;
use App\Containers\Monitoring\Educator\Tasks\UpdateEducatorTask;
use App\Containers\Monitoring\Educator\UI\API\Requests\UpdateEducatorRequest;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Support\Facades\DB;

final class UpdateEducatorAction extends ParentAction
{
    public function __construct(
        private readonly UpdateEducatorTask $updateEducatorTask,
        private readonly UpdateUserTask $updateUserTask,
        private readonly EducatorRepository $educatorRepository,
    ) {
    }

    public function run(UpdateEducatorRequest $request): Educator
    {
        // Preparar solo los campos que se pueden actualizar del Educator
        // Nota: El email NO se puede cambiar (pertenece al User y no se actualiza)
        $data = array_filter([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'gender' => $request->input('gender'),
            'birth' => $request->input('birth'),
            'state' => $request->input('state'),
            'dni' => $request->input('dni'),
            'phone' => $request->input('phone'),
        ], fn ($value) => $value !== null);

        return DB::transaction(function () use ($data, $request) {
            // Obtener el educador antes de actualizar para verificar si tiene user_id
            $educator = $this->educatorRepository->find($request->id);
            
            if (!$educator) {
                throw new \Illuminate\Database\Eloquent\ModelNotFoundException("Educator not found");
            }

            // Verificar si se están actualizando first_name o last_name
            $shouldUpdateUserName = isset($data['first_name']) || isset($data['last_name']);

            // Actualizar el educador
            $educator = $this->updateEducatorTask->run($data, $request->id);

            // Si se actualizaron first_name o last_name y el educador tiene un usuario relacionado,
            // actualizar también el campo name del usuario
            if ($shouldUpdateUserName && $educator->user_id && $educator->user) {
                $fullName = trim("{$educator->first_name} {$educator->last_name}");
                if (!empty($fullName)) {
                    $this->updateUserTask->run($educator->user_id, ['name' => $fullName]);
                }
            }

            // Cargar relación user para el transformer
            $educator->load('user');

            return $educator;
        });
    }
}

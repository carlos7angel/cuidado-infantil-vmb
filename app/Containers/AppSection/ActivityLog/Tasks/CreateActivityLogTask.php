<?php

namespace App\Containers\AppSection\ActivityLog\Tasks;

use App\Containers\AppSection\ActivityLog\Data\Repositories\ActivityLogRepository;
use App\Containers\AppSection\ActivityLog\Constants\LogConstants;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Facades\Activity;
use Spatie\Activitylog\Contracts\Activity as ActivityContract;

final class CreateActivityLogTask extends ParentTask
{
    public function __construct(
        private readonly ActivityLogRepository $repository,
    ) {
    }

    public function run($inputLog, $request, $subject, $inputData = null): ?ActivityContract
    {
        try {
            $log_data = true;
            $log = $message = '';
            $is_logged = true;
            switch ($inputLog) {
                case LogConstants::LOGIN_ADMIN:
                    $log = 'INGRESO AL SISTEMA';
                    $message = "El usuario " . $subject->name . " (" . $subject->email . ") ha ingresado al sistema";
                    $log_data = false;
                    $is_logged = true; // El usuario que se loguea debe ser registrado como causer
                    break;
                case LogConstants::REGISTER_CHILD:
                    $log = 'REGISTRO DE INFANTE CREADO';
                    $childName = method_exists($subject, 'fullName') ? $subject->fullName : ($subject->first_name ?? 'Infante');
                    $message = "Nuevo infante {$childName} creado";
                    break;
                default:
                    throw new \Exception('Activity Log Type Not Found');
                    break;
            }

            $data = [];
            if ($log_data) {
                if (empty($inputData)) {
                    $data = $subject->toArray();
                } else {
                    $data = $inputData;
                }
            }

            $causer = null;
            if ($is_logged) {
                // Para LOGIN_ADMIN, el subject (usuario) es quien se loguea
                if ($inputLog === LogConstants::LOGIN_ADMIN) {
                    $causer = $subject; // El usuario que se loguea
                } else {
                    // Para otros logs, intentar obtener el usuario autenticado
                    $causer = Auth::check() ? Auth::user() : null;
                }
            }

            $properties = $data;
            
            $activity = activity($log)
            ->causedBy($causer)
            ->performedOn($subject)
            ->withProperties($properties)
            ->log($message);
            
            if (isset($request['HTTP_USER_AGENT'])) {
                $activity->user_agent = $request['HTTP_USER_AGENT'];
            }
            if (isset($request['REMOTE_ADDR'])) {
                $activity->ip_address = $request['REMOTE_ADDR'];
            }
            $activity->save();
            
            return $activity;
        } catch (\Exception $e) {
            throw new \Exception('Error al crear el registro de actividad: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Containers\AppSection\ActivityLog\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Spatie\Activitylog\Models\Activity as SpatieActivity;

/**
 * Modelo personalizado para ActivityLog que extiende el modelo de Spatie.
 * Este modelo se usa para consultas personalizadas y puede tener métodos adicionales.
 * Para crear logs, usar el helper activity() de Spatie que retorna el modelo de Spatie.
 */
final class ActivityLog extends SpatieActivity
{

    protected $fillable = [
        'log_name',
        'description',
        'subject_id',
        'subject_type',
        'event',
        'causer_id',
        'causer_type',
        'properties',
        'ip_address',
        'user_agent',
        'batch_uuid',
    ];

    protected $casts = [
        'properties' => 'collection',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

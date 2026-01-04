<?php

namespace App\Containers\Monitoring\ChildEnrollment\Enums;

enum EnrollmentStatus: string
{
    case ACTIVE = 'activo';
    case INACTIVE = 'inactivo';
    case GRADUATED = 'egresado';
    case TRANSFERRED = 'trasladado';
    case WITHDRAWN = 'retirado';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Activo',
            self::INACTIVE => 'Inactivo',
            self::GRADUATED => 'Egresado',
            self::TRANSFERRED => 'Trasladado',
            self::WITHDRAWN => 'Retirado',
        };
    }
}

<?php

namespace App\Containers\Monitoring\Child\Enums;

enum HousingType: string
{
    case HOUSE = 'casa';
    case APARTMENT = 'departamento';
    case ROOM = 'cuarto';
    case OTHER = 'otro';

    public function label(): string
    {
        return match ($this) {
            self::HOUSE => 'Casa',
            self::APARTMENT => 'Departamento',
            self::ROOM => 'Cuarto',
            self::OTHER => 'Otro',
        };
    }
}

<?php

namespace App\Containers\Monitoring\Child\Enums;

enum HousingTenure: string
{
    case OWNED = 'propio';
    case RENTED = 'alquiler';
    case ANTICHRESIS = 'anticretico';
    case FAMILY = 'familiar';
    case CARETAKER = 'cuidador';

    public function label(): string
    {
        return match ($this) {
            self::OWNED => 'Propio',
            self::RENTED => 'Alquiler',
            self::ANTICHRESIS => 'Anticrético',
            self::FAMILY => 'Familiar',
            self::CARETAKER => 'Cuidador',
        };
    }
}

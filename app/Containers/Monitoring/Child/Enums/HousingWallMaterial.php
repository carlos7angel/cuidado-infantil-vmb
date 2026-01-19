<?php

namespace App\Containers\Monitoring\Child\Enums;

enum HousingWallMaterial: string
{
    case WOOD = 'madera';
    case BRICK = 'ladrillo';
    case ADOBE = 'adobe';

    public function label(): string
    {
        return match ($this) {
            self::WOOD => 'Madera',
            self::BRICK => 'Ladrillo',
            self::ADOBE => 'Adobe',
        };
    }
}

<?php

namespace App\Containers\Monitoring\Child\Enums;

enum HousingFloorMaterial: string
{
    case EARTH = 'tierra';
    case CEMENT = 'cemento';
    case TONGUE_AND_GROOVE = 'machimbre';
    case PARQUET = 'parquet';

    public function label(): string
    {
        return match ($this) {
            self::EARTH => 'Tierra',
            self::CEMENT => 'Cemento',
            self::TONGUE_AND_GROOVE => 'Machimbre',
            self::PARQUET => 'Parquet',
        };
    }
}

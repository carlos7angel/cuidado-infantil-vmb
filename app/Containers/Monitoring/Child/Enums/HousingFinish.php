<?php

namespace App\Containers\Monitoring\Child\Enums;

enum HousingFinish: string
{
    case FINE_WORK = 'obra_fina';
    case ROUGH_WORK = 'obra_gruesa';

    public function label(): string
    {
        return match ($this) {
            self::FINE_WORK => 'Obra fina',
            self::ROUGH_WORK => 'Obra gruesa',
        };
    }
}

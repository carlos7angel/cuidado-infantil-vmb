<?php

namespace App\Containers\Monitoring\Child\Enums;

enum HousingUtility: string
{
    case DRINKING_WATER = 'agua_potable';
    case ELECTRICITY = 'energia_electrica';
    case SEWERAGE = 'alcantarillado';
    case GAS = 'gas';
    case PHONE = 'telefono';
    case INTERNET = 'internet';

    public function label(): string
    {
        return match ($this) {
            self::DRINKING_WATER => 'Agua Potable',
            self::ELECTRICITY => 'Energía Eléctrica',
            self::SEWERAGE => 'Alcantarillado',
            self::GAS => 'Gas',
            self::PHONE => 'Teléfono',
            self::INTERNET => 'Internet',
        };
    }
}


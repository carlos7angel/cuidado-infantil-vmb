<?php

namespace App\Containers\Monitoring\Child\Enums;

enum TravelTime: string
{
    case LESS_THAN_HALF_HOUR = 'menos_media_hora';
    case HALF_TO_ONE_HOUR = 'media_a_una_hora';
    case MORE_THAN_ONE_HOUR = 'mas_de_una_hora';

    public function label(): string
    {
        return match ($this) {
            self::LESS_THAN_HALF_HOUR => 'Menos de Media Hora',
            self::HALF_TO_ONE_HOUR => 'Media a Una Hora',
            self::MORE_THAN_ONE_HOUR => 'Más de Una Hora',
        };
    }
}

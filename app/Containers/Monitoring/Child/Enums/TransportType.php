<?php

namespace App\Containers\Monitoring\Child\Enums;

enum TransportType: string
{
    case OWN = 'propio';
    case PUBLIC = 'publico';
    case WALKING = 'a_pie';

    public function label(): string
    {
        return match ($this) {
            self::OWN => 'Propio',
            self::PUBLIC => 'Público',
            self::WALKING => 'A Pie',
        };
    }
}

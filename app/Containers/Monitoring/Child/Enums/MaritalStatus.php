<?php

namespace App\Containers\Monitoring\Child\Enums;

enum MaritalStatus: string
{
    case SINGLE = 'soltero';
    case MARRIED = 'casado';
    case DIVORCED = 'divorciado';
    case WIDOWED = 'viudo';
    case COHABITING = 'concubino';
    case SEPARATED = 'separado';

    public function label(): string
    {
        return match ($this) {
            self::SINGLE => 'Soltero',
            self::MARRIED => 'Casado',
            self::DIVORCED => 'Divorciado',
            self::WIDOWED => 'Viudo',
            self::COHABITING => 'Concubino',
            self::SEPARATED => 'Separado',
        };
    }
}

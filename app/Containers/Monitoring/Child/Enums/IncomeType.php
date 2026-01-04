<?php

namespace App\Containers\Monitoring\Child\Enums;

enum IncomeType: string
{
    case DAILY = 'diario';
    case WEEKLY = 'semanal';
    case BIWEEKLY = 'quincenal';
    case MONTHLY = 'mensual';

    public function label(): string
    {
        return match ($this) {
            self::DAILY => 'Diario',
            self::WEEKLY => 'Semanal',
            self::BIWEEKLY => 'Quincenal',
            self::MONTHLY => 'Mensual',
        };
    }
}

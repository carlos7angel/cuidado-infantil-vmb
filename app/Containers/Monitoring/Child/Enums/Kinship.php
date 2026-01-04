<?php

namespace App\Containers\Monitoring\Child\Enums;

enum Kinship: string
{
    case MOTHER = 'madre';
    case FATHER = 'padre';
    case SIBLING = 'hermano';
    case GRANDMOTHER = 'abuela';
    case GRANDFATHER = 'abuelo';
    case UNCLE = 'tio';
    case AUNT = 'tia';
    case COUSIN = 'primo';
    case STEPFATHER = 'padrastro';
    case STEPMOTHER = 'madrastra';
    case OTHER = 'otro';

    public function label(): string
    {
        return match ($this) {
            self::MOTHER => 'Madre',
            self::FATHER => 'Padre',
            self::SIBLING => 'Hermano',
            self::GRANDMOTHER => 'Abuela',
            self::GRANDFATHER => 'Abuelo',
            self::UNCLE => 'Tío',
            self::AUNT => 'Tía',
            self::COUSIN => 'Primo',
            self::STEPFATHER => 'Padrastro',
            self::STEPMOTHER => 'Madrastra',
            self::OTHER => 'Otro',
        };
    }
}

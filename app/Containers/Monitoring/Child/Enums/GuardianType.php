<?php

namespace App\Containers\Monitoring\Child\Enums;

enum GuardianType: string
{
    case MOTHER = 'madre';
    case FATHER = 'padre';
    case BOTH = 'ambos';
    case TUTOR = 'tutor';
    case OLDER_SIBLING = 'hermano';
    case GRANDPARENTS = 'abuelos';
    case UNCLES = 'tios';
    case OTHER = 'otros';

    public function label(): string
    {
        return match ($this) {
            self::MOTHER => 'Madre',
            self::FATHER => 'Padre',
            self::BOTH => 'Ambos',
            self::TUTOR => 'Tutor',
            self::OLDER_SIBLING => 'Hermano',
            self::GRANDPARENTS => 'Abuelos',
            self::UNCLES => 'Tíos',
            self::OTHER => 'Otros',
        };
    }
}

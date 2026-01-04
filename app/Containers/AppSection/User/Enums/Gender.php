<?php

namespace App\Containers\AppSection\User\Enums;

enum Gender: string
{
    case MALE = 'masculino';
    case FEMALE = 'femenino';
    case UNSPECIFIED = 'no_especificado';

    public function label(): string
    {
        return match ($this) {
            self::MALE => 'Masculino',
            self::FEMALE => 'Femenino',
            self::UNSPECIFIED => 'No Especificado',
        };
    }
}

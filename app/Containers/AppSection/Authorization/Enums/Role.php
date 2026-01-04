<?php

namespace App\Containers\AppSection\Authorization\Enums;

enum Role: string
{
    case SUPER_ADMIN = 'super';
    case MUNICIPAL_ADMIN = 'municipal_admin';
    case CHILDCARE_ADMIN = 'childcare_admin';
    case EDUCATOR = 'educator';

    public function label(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'Super Administrador',
            self::MUNICIPAL_ADMIN => 'Administrador Municipio',
            self::CHILDCARE_ADMIN => 'Administrador CCI',
            self::EDUCATOR => 'Educador',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'Super Administrador',
            self::MUNICIPAL_ADMIN => 'Administrador a nivel de Municipio',
            self::CHILDCARE_ADMIN => 'Administrador de un Centro de Cuidado Infantil',
            self::EDUCATOR => 'Educador',
        };
    }
}

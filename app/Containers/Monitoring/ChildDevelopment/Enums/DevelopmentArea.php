<?php

namespace App\Containers\Monitoring\ChildDevelopment\Enums;

/**
 * Áreas de desarrollo infantil.
 *
 * Representa las diferentes áreas evaluadas en el desarrollo de la primera infancia.
 */
enum DevelopmentArea: string
{
    case MOTOR_GROSS = 'MG';      // Motricidad Gruesa
    case MOTOR_FINE = 'MF';       // Motricidad Fina
    case LANGUAGE = 'AL';         // Área del Lenguaje
    case PERSONAL_SOCIAL = 'PS';  // Personal Social

    /**
     * Get human-readable label for the area.
     */
    public function label(): string
    {
        return match ($this) {
            self::MOTOR_GROSS => 'Motricidad Gruesa',
            self::MOTOR_FINE => 'Motricidad Fina',
            self::LANGUAGE => 'Área del Lenguaje',
            self::PERSONAL_SOCIAL => 'Personal Social',
        };
    }

    /**
     * Get short abbreviation.
     */
    public function abbreviation(): string
    {
        return $this->value;
    }

    /**
     * Get all areas as array for select options.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return array_column(self::cases(), 'value', 'name');
    }

    /**
     * Get all areas with labels for select options.
     *
     * @return array<string, string>
     */
    public static function optionsWithLabels(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }

        return $options;
    }
}


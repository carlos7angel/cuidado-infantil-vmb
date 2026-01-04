<?php

namespace App\Containers\Monitoring\ChildDevelopment\Enums;

/**
 * Estado/Interpretación del desarrollo según puntajes.
 *
 * Clasifica el nivel de desarrollo del niño según los puntajes obtenidos
 * en cada área, basado en las normas de desarrollo establecidas.
 */
enum DevelopmentStatus: string
{
    case ALERT = 'alerta';           // Alerta - Requiere atención inmediata
    case MEDIUM = 'medio';            // Medio - Desarrollo dentro del rango esperado bajo
    case MEDIUM_HIGH = 'medio_alto';  // Medio Alto - Desarrollo dentro del rango esperado alto
    case HIGH = 'alto';               // Alto - Desarrollo óptimo

    /**
     * Get human-readable label for the status.
     */
    public function label(): string
    {
        return match ($this) {
            self::ALERT => 'Alerta',
            self::MEDIUM => 'Medio',
            self::MEDIUM_HIGH => 'Medio Alto',
            self::HIGH => 'Alto',
        };
    }

    /**
     * Get description/interpretation of the status.
     */
    public function description(): string
    {
        return match ($this) {
            self::ALERT => 'Requiere atención inmediata - Desarrollo por debajo de lo esperado',
            self::MEDIUM => 'Desarrollo dentro del rango esperado (nivel bajo)',
            self::MEDIUM_HIGH => 'Desarrollo dentro del rango esperado (nivel alto)',
            self::HIGH => 'Desarrollo óptimo - Por encima del promedio esperado',
        };
    }

    /**
     * Check if status requires attention/intervention.
     */
    public function requiresAttention(): bool
    {
        return $this === self::ALERT;
    }

    /**
     * Check if status is within normal range.
     */
    public function isNormal(): bool
    {
        return in_array($this, [self::MEDIUM, self::MEDIUM_HIGH, self::HIGH]);
    }

    /**
     * Get color code for UI (e.g., alerts, badges).
     */
    public function color(): string
    {
        return match ($this) {
            self::ALERT => 'red',
            self::MEDIUM => 'yellow',
            self::MEDIUM_HIGH => 'blue',
            self::HIGH => 'green',
        };
    }

    /**
     * Get all statuses as array for select options.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return array_column(self::cases(), 'value', 'name');
    }

    /**
     * Get all statuses with labels for select options.
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


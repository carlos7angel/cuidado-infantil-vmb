<?php

namespace App\Containers\Monitoring\IncidentReport\Enums;

/**
 * Niveles de gravedad de incidentes.
 */
enum IncidentSeverity: string
{
    case MILD = 'leve';
    case MODERATE = 'moderado';
    case SEVERE = 'grave';
    case CRITICAL = 'critico';

    /**
     * Get human-readable label.
     */
    public function label(): string
    {
        return match ($this) {
            self::MILD => 'Leve',
            self::MODERATE => 'Moderado',
            self::SEVERE => 'Grave',
            self::CRITICAL => 'Crítico',
        };
    }

    /**
     * Get description.
     */
    public function description(): string
    {
        return match ($this) {
            self::MILD => 'Incidente menor que requiere seguimiento básico',
            self::MODERATE => 'Incidente que requiere atención y seguimiento',
            self::SEVERE => 'Incidente grave que requiere acción inmediata',
            self::CRITICAL => 'Incidente crítico que requiere intervención urgente y notificación a autoridades',
        };
    }

    /**
     * Get color for UI.
     */
    public function color(): string
    {
        return match ($this) {
            self::MILD => '#4CAF50',      // Verde
            self::MODERATE => '#FF9800',  // Naranja
            self::SEVERE => '#F44336',    // Rojo
            self::CRITICAL => '#9C27B0',  // Morado
        };
    }

    /**
     * Check if severity requires immediate attention.
     */
    public function requiresImmediateAttention(): bool
    {
        return in_array($this, [self::SEVERE, self::CRITICAL], true);
    }

    /**
     * Check if severity requires authority notification.
     */
    public function requiresAuthorityNotification(): bool
    {
        return $this === self::CRITICAL;
    }

    /**
     * Get priority level (1-4, where 1 is highest).
     */
    public function priority(): int
    {
        return match ($this) {
            self::CRITICAL => 1,
            self::SEVERE => 2,
            self::MODERATE => 3,
            self::MILD => 4,
        };
    }

    /**
     * Get all options as array.
     */
    public static function options(): array
    {
        return array_map(
            fn (self $severity) => [
                'value' => $severity->value,
                'label' => $severity->label(),
                'description' => $severity->description(),
                'color' => $severity->color(),
                'priority' => $severity->priority(),
            ],
            self::cases()
        );
    }
}


<?php

namespace App\Containers\Monitoring\IncidentReport\Enums;

/**
 * Estados del reporte de incidente.
 */
enum IncidentStatus: string
{
    case REPORTED = 'reportado';
    case UNDER_REVIEW = 'en_revision';
    case CLOSED = 'cerrado';
    case ESCALATED = 'escalado';
    case ARCHIVED = 'archivado';

    /**
     * Get human-readable label.
     */
    public function label(): string
    {
        return match ($this) {
            self::REPORTED => 'Reportado',
            self::UNDER_REVIEW => 'En Revisión',
            self::CLOSED => 'Cerrado',
            self::ESCALATED => 'Escalado',
            self::ARCHIVED => 'Archivado',
        };
    }

    /**
     * Get description.
     */
    public function description(): string
    {
        return match ($this) {
            self::REPORTED => 'El incidente ha sido reportado y está pendiente de revisión',
            self::UNDER_REVIEW => 'El incidente está siendo revisado por el personal autorizado',
            self::CLOSED => 'El incidente ha sido cerrado y resuelto',
            self::ESCALATED => 'El incidente ha sido escalado a autoridades superiores',
            self::ARCHIVED => 'El incidente ha sido archivado',
        };
    }

    /**
     * Check if status is active (not closed or archived).
     */
    public function isActive(): bool
    {
        return !in_array($this, [self::CLOSED, self::ARCHIVED], true);
    }

    /**
     * Check if status requires attention.
     */
    public function requiresAttention(): bool
    {
        return in_array($this, [self::REPORTED, self::UNDER_REVIEW, self::ESCALATED], true);
    }

    /**
     * Get all options as array.
     */
    public static function options(): array
    {
        return array_map(
            fn (self $status) => [
                'value' => $status->value,
                'label' => $status->label(),
                'description' => $status->description(),
            ],
            self::cases()
        );
    }
}


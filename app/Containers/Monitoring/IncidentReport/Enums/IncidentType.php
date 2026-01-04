<?php

namespace App\Containers\Monitoring\IncidentReport\Enums;

/**
 * Tipos de incidentes y maltrato infantil.
 */
enum IncidentType: string
{
    case ACCIDENT = 'accidente';
    case INAPPROPRIATE_BEHAVIOR = 'conducta_inapropiada';
    case PHYSICAL_INJURY = 'lesion_fisica';
    case NEGLIGENCE = 'negligencia';
    case PSYCHOLOGICAL_ABUSE = 'maltrato_psicologico';
    case PHYSICAL_ABUSE = 'maltrato_fisico';
    case OTHER = 'otro';

    /**
     * Get human-readable label.
     */
    public function label(): string
    {
        return match ($this) {
            self::ACCIDENT => 'Accidente',
            self::INAPPROPRIATE_BEHAVIOR => 'Conducta Inapropiada',
            self::PHYSICAL_INJURY => 'Lesión Física',
            self::NEGLIGENCE => 'Negligencia',
            self::PSYCHOLOGICAL_ABUSE => 'Maltrato Psicológico',
            self::PHYSICAL_ABUSE => 'Maltrato Físico',
            self::OTHER => 'Otro',
        };
    }

    /**
     * Get description.
     */
    public function description(): string
    {
        return match ($this) {
            self::ACCIDENT => 'Accidente o lesión no intencional',
            self::INAPPROPRIATE_BEHAVIOR => 'Conducta inapropiada de personal o terceros',
            self::PHYSICAL_INJURY => 'Lesión física al niño',
            self::NEGLIGENCE => 'Negligencia en el cuidado del niño',
            self::PSYCHOLOGICAL_ABUSE => 'Maltrato psicológico o emocional',
            self::PHYSICAL_ABUSE => 'Maltrato físico',
            self::OTHER => 'Otro tipo de incidente',
        };
    }

    /**
     * Check if type requires immediate attention.
     */
    public function requiresImmediateAttention(): bool
    {
        return in_array($this, [
            self::PHYSICAL_ABUSE,
            self::PSYCHOLOGICAL_ABUSE,
            self::PHYSICAL_INJURY,
            self::NEGLIGENCE,
        ], true);
    }

    /**
     * Check if type requires authority notification.
     */
    public function requiresAuthorityNotification(): bool
    {
        return in_array($this, [
            self::PHYSICAL_ABUSE,
            self::PSYCHOLOGICAL_ABUSE,
            self::NEGLIGENCE,
        ], true);
    }

    /**
     * Get severity level suggestion.
     */
    public function defaultSeverity(): string
    {
        return match ($this) {
            self::PHYSICAL_ABUSE, self::PSYCHOLOGICAL_ABUSE => 'critico',
            self::PHYSICAL_INJURY, self::NEGLIGENCE => 'grave',
            self::INAPPROPRIATE_BEHAVIOR => 'moderado',
            self::ACCIDENT, self::OTHER => 'leve',
        };
    }

    /**
     * Get all options as array.
     */
    public static function options(): array
    {
        return array_map(
            fn (self $type) => [
                'value' => $type->value,
                'label' => $type->label(),
                'description' => $type->description(),
                'requires_immediate_attention' => $type->requiresImmediateAttention(),
                'requires_authority_notification' => $type->requiresAuthorityNotification(),
            ],
            self::cases()
        );
    }
}


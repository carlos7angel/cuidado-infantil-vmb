<?php

namespace App\Containers\Monitoring\NutritionalAssessment\Enums;

/**
 * Clasificación nutricional según desviaciones estándar (Z-score) de la OMS.
 *
 * Basado en las tablas oficiales de la OMS para diferentes indicadores.
 * Cada indicador tiene su propia interpretación según los rangos de Z-score.
 */
enum NutritionalStatus: string
{
    // Casos para valores altos/sobrepeso/obesidad
    case VERY_HIGH = 'muy_alto';              // Peso/Talla muy alto
    case HIGH = 'alto';                       // Peso/Talla alto
    case OBESITY = 'obesidad';                // Obesidad (IMC, Peso/Talla)
    case OVERWEIGHT = 'sobrepeso';            // Sobrepeso
    case OVERWEIGHT_RISK = 'riesgo_sobrepeso'; // Riesgo de sobrepeso
    
    // Casos para valores normales
    case NORMAL = 'normal';                   // Normal
    
    // Casos para valores bajos/desnutrición
    case UNDERWEIGHT_RISK = 'riesgo_desnutricion';     // Riesgo de desnutrición
    case UNDERWEIGHT = 'bajo_peso';                    // Bajo peso / Delgadez / Talla baja
    case SEVERE_UNDERWEIGHT = 'bajo_peso_severo';      // Bajo peso severo / Delgadez severa / Talla baja severa

    /**
     * Get status from Z-score value (método genérico - usar métodos específicos cuando sea posible).
     * 
     * @deprecated Usar métodos específicos: fromWeightForAgeZScore, fromHeightForAgeZScore, etc.
     */
    public static function fromZScore(float $zScore): self
    {
        return self::fromWeightForAgeZScore($zScore);
    }

    /**
     * Interpretación para Weight for Age (Peso/Edad - WFA).
     * Según tablas oficiales OMS:
     * - < -3: Bajo peso severo
     * - -3 a < -2: Bajo peso
     * - -2 a +2: Normal
     * - > +2: Alto
     * - > +3: Muy alto
     */
    public static function fromWeightForAgeZScore(float $zScore): self
    {
        return match (true) {
            $zScore > 3 => self::VERY_HIGH,        // Muy alto
            $zScore > 2 => self::HIGH,            // Alto
            $zScore >= -2 => self::NORMAL,        // Normal
            $zScore >= -3 => self::UNDERWEIGHT,    // Bajo peso
            default => self::SEVERE_UNDERWEIGHT,   // Bajo peso severo
        };
    }

    /**
     * Interpretación para Height/Length for Age (Talla/Longitud/Edad - LFA/HFA).
     * Según tablas oficiales OMS:
     * - < -3: Talla baja severa
     * - -3 a < -2: Talla baja
     * - -2 a +2: Normal
     * - > +2: Talla alta
     * - > +3: Talla muy alta
     */
    public static function fromHeightForAgeZScore(float $zScore): self
    {
        return match (true) {
            $zScore > 3 => self::VERY_HIGH,        // Talla muy alta
            $zScore > 2 => self::HIGH,             // Talla alta
            $zScore >= -2 => self::NORMAL,         // Normal
            $zScore >= -3 => self::UNDERWEIGHT,    // Talla baja
            default => self::SEVERE_UNDERWEIGHT,   // Talla baja severa
        };
    }

    /**
     * Interpretación para Weight for Height/Length (Peso/Talla o Peso/Longitud - WFL/WFH).
     * Según tablas oficiales OMS:
     * - < -3: Delgadez severa (Desnutrición aguda severa)
     * - -3 a < -2: Delgadez (Desnutrición aguda)
     * - -2 a +2: Normal (Peso proporcional a la estatura)
     * - > +2: Sobrepeso (Exceso de peso)
     * - > +3: Obesidad (Exceso severo de peso)
     */
    public static function fromWeightForHeightZScore(float $zScore): self
    {
        return match (true) {
            $zScore > 3 => self::OBESITY,          // Obesidad (Exceso severo de peso)
            $zScore > 2 => self::OVERWEIGHT,       // Sobrepeso (Exceso de peso)
            $zScore >= -2 => self::NORMAL,        // Normal (Peso proporcional a la estatura)
            $zScore >= -3 => self::UNDERWEIGHT,    // Delgadez (Desnutrición aguda)
            default => self::SEVERE_UNDERWEIGHT,    // Delgadez severa (Desnutrición aguda severa)
        };
    }

    /**
     * Interpretación para BMI for Age (IMC/Edad).
     * Según tablas oficiales OMS:
     * - < -3: Delgadez severa
     * - -3 a < -2: Delgadez
     * - -2 a +1: Normal
     * - > +1 a +2: Riesgo de sobrepeso
     * - > +2: Sobrepeso
     * - > +3: Obesidad
     */
    public static function fromBmiForAgeZScore(float $zScore): self
    {
        return match (true) {
            $zScore > 3 => self::OBESITY,          // Obesidad (Obesidad marcada)
            $zScore > 2 => self::OVERWEIGHT,       // Sobrepeso (Exceso de grasa corporal)
            $zScore > 1 => self::OVERWEIGHT_RISK,  // Riesgo de sobrepeso (Tendencia a exceso de peso)
            $zScore >= -2 => self::NORMAL,        // Normal (IMC adecuado para la edad)
            $zScore >= -3 => self::UNDERWEIGHT,    // Delgadez (IMC bajo para la edad)
            default => self::SEVERE_UNDERWEIGHT,   // Delgadez severa (IMC muy bajo para la edad)
        };
    }

    /**
     * Get human-readable label (clasificación genérica).
     * 
     * @deprecated Usar métodos específicos: labelForWeightForAge(), labelForHeightForAge(), etc.
     */
    public function label(): string
    {
        return match ($this) {
            self::VERY_HIGH => 'Muy alto',
            self::HIGH => 'Alto',
            self::OBESITY => 'Obesidad',
            self::OVERWEIGHT => 'Sobrepeso',
            self::OVERWEIGHT_RISK => 'Riesgo de sobrepeso',
            self::NORMAL => 'Normal',
            self::UNDERWEIGHT_RISK => 'Riesgo de desnutrición',
            self::UNDERWEIGHT => 'Bajo peso / Delgadez / Talla baja',
            self::SEVERE_UNDERWEIGHT => 'Bajo peso severo / Delgadez severa / Talla baja severa',
        };
    }

    /**
     * Get label for Weight for Age (Peso/Edad).
     */
    public function labelForWeightForAge(): string
    {
        return match ($this) {
            self::VERY_HIGH => 'Peso muy alto',
            self::HIGH => 'Peso alto',
            self::OBESITY => 'Obesidad',
            self::OVERWEIGHT => 'Sobrepeso',
            self::OVERWEIGHT_RISK => 'Riesgo de sobrepeso',
            self::NORMAL => 'Normal',
            self::UNDERWEIGHT_RISK => 'Riesgo de bajo peso',
            self::UNDERWEIGHT => 'Bajo peso',
            self::SEVERE_UNDERWEIGHT => 'Bajo peso severo',
        };
    }

    /**
     * Get label for Height/Length for Age (Talla/Longitud/Edad).
     */
    public function labelForHeightForAge(): string
    {
        return match ($this) {
            self::VERY_HIGH => 'Talla muy alta',
            self::HIGH => 'Talla alta',
            self::OBESITY => 'Obesidad',
            self::OVERWEIGHT => 'Sobrepeso',
            self::OVERWEIGHT_RISK => 'Riesgo de sobrepeso',
            self::NORMAL => 'Normal',
            self::UNDERWEIGHT_RISK => 'Riesgo de talla baja',
            self::UNDERWEIGHT => 'Talla baja',
            self::SEVERE_UNDERWEIGHT => 'Talla baja severa',
        };
    }

    /**
     * Get label for Weight for Height/Length (Peso/Talla o Peso/Longitud).
     */
    public function labelForWeightForHeight(): string
    {
        return match ($this) {
            self::VERY_HIGH => 'Peso muy alto',
            self::HIGH => 'Peso alto',
            self::OBESITY => 'Obesidad',
            self::OVERWEIGHT => 'Sobrepeso',
            self::OVERWEIGHT_RISK => 'Riesgo de sobrepeso',
            self::NORMAL => 'Normal',
            self::UNDERWEIGHT_RISK => 'Riesgo de delgadez',
            self::UNDERWEIGHT => 'Delgadez',
            self::SEVERE_UNDERWEIGHT => 'Delgadez severa',
        };
    }

    /**
     * Get label for BMI for Age (IMC/Edad).
     */
    public function labelForBmiForAge(): string
    {
        return match ($this) {
            self::VERY_HIGH => 'IMC muy alto',
            self::HIGH => 'IMC alto',
            self::OBESITY => 'Obesidad',
            self::OVERWEIGHT => 'Sobrepeso',
            self::OVERWEIGHT_RISK => 'Riesgo de sobrepeso',
            self::NORMAL => 'Normal',
            self::UNDERWEIGHT_RISK => 'Riesgo de bajo IMC',
            self::UNDERWEIGHT => 'Bajo IMC',
            self::SEVERE_UNDERWEIGHT => 'Bajo IMC severo',
        };
    }

    /**
     * Get interpretation for Weight for Age (Peso/Edad).
     * Retorna la interpretación según la columna "Qué mide / interpretación" de las tablas OMS.
     */
    public function interpretationForWeightForAge(): string
    {
        return match ($this) {
            self::VERY_HIGH => 'Peso mucho más alto que el promedio',
            self::HIGH => 'Peso más alto que el promedio',
            self::NORMAL => 'Peso adecuado para la edad',
            self::UNDERWEIGHT => 'Peso bajo para la edad',
            self::SEVERE_UNDERWEIGHT => 'Peso muy bajo para la edad',
            default => $this->label(),
        };
    }

    /**
     * Get interpretation for Height/Length for Age (Talla/Longitud/Edad).
     * Retorna la interpretación según la columna "Qué mide / interpretación" de las tablas OMS.
     */
    public function interpretationForHeightForAge(): string
    {
        return match ($this) {
            self::VERY_HIGH => 'Crecimiento lineal mucho mayor que el promedio',
            self::HIGH => 'Crecimiento lineal por encima del promedio',
            self::NORMAL => 'Crecimiento lineal adecuado',
            self::UNDERWEIGHT => 'Crecimiento lineal bajo',
            self::SEVERE_UNDERWEIGHT => 'Crecimiento lineal muy bajo',
            default => $this->label(),
        };
    }

    /**
     * Get interpretation for Weight for Height/Length (Peso/Talla o Peso/Longitud).
     * Retorna la interpretación según la columna "Qué mide / interpretación" de las tablas OMS.
     */
    public function interpretationForWeightForHeight(): string
    {
        return match ($this) {
            self::OBESITY => 'Exceso severo de peso',
            self::OVERWEIGHT => 'Exceso de peso',
            self::NORMAL => 'Peso proporcional a la estatura',
            self::UNDERWEIGHT => 'Desnutrición aguda',
            self::SEVERE_UNDERWEIGHT => 'Desnutrición aguda severa',
            default => $this->label(),
        };
    }

    /**
     * Get interpretation for BMI for Age (IMC/Edad).
     * Retorna la interpretación según la columna "Qué mide / interpretación" de las tablas OMS.
     */
    public function interpretationForBmiForAge(): string
    {
        return match ($this) {
            self::OBESITY => 'Obesidad marcada',
            self::OVERWEIGHT => 'Exceso de grasa corporal',
            self::OVERWEIGHT_RISK => 'Tendencia a exceso de peso',
            self::NORMAL => 'IMC adecuado para la edad',
            self::UNDERWEIGHT => 'IMC bajo para la edad',
            self::SEVERE_UNDERWEIGHT => 'IMC muy bajo para la edad',
            default => $this->label(),
        };
    }

    /**
     * Check if status requires attention/intervention.
     */
    public function requiresAttention(): bool
    {
        return in_array($this, [
            self::VERY_HIGH,
            self::HIGH,
            self::OBESITY,
            self::OVERWEIGHT,
            self::UNDERWEIGHT,
            self::SEVERE_UNDERWEIGHT,
        ]);
    }

    /**
     * Get color code for UI (e.g., alerts).
     */
    public function color(): string
    {
        return match ($this) {
            self::VERY_HIGH => 'red',
            self::HIGH => 'orange',
            self::OBESITY => 'red',
            self::OVERWEIGHT => 'orange',
            self::OVERWEIGHT_RISK => 'yellow',
            self::NORMAL => 'green',
            self::UNDERWEIGHT_RISK => 'yellow',
            self::UNDERWEIGHT => 'orange',
            self::SEVERE_UNDERWEIGHT => 'red',
        };
    }
}

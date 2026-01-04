<?php

namespace App\Containers\Monitoring\NutritionalAssessment\Models;

use App\Ship\Parents\Models\Model as ParentModel;

/**
 * Modelo para la tabla de referencia WHO: Weight for Length.
 * 
 * Contiene los parámetros LMS y desviaciones estándar precalculadas
 * para calcular z-scores de peso según longitud y género.
 * Rango: 0-2 años - Medición acostado
 */
final class WhoWeightForLength extends ParentModel
{
    /**
     * Nombre de la tabla.
     */
    protected $table = 'who_weight_for_length';

    /**
     * Deshabilitar timestamps (tabla de referencia estática).
     */
    public $timestamps = false;

    /**
     * Campos que pueden ser asignados en masa.
     * 
     * @var list<string>
     */
    protected $fillable = [
        'length',
        'gender',
        'L',
        'M',
        'S',
        'SD3neg',
        'SD2neg',
        'SD1neg',
        'SD0',
        'SD1',
        'SD2',
        'SD3',
    ];

    /**
     * Casts de atributos.
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'length' => 'decimal:2',
        'gender' => 'integer',
        'L' => 'decimal:5',
        'M' => 'decimal:5',
        'S' => 'decimal:5',
        'SD3neg' => 'decimal:2',
        'SD2neg' => 'decimal:2',
        'SD1neg' => 'decimal:2',
        'SD0' => 'decimal:2',
        'SD1' => 'decimal:2',
        'SD2' => 'decimal:2',
        'SD3' => 'decimal:2',
    ];

    // ==========================================================================
    // Scopes
    // ==========================================================================

    /**
     * Scope para filtrar por longitud en cm.
     */
    public function scopeForLength($query, float $length)
    {
        return $query->where('length', $length);
    }

    /**
     * Scope para filtrar por género.
     * 
     * @param int $gender 1 = Masculino, 2 = Femenino
     */
    public function scopeForGender($query, int $gender)
    {
        return $query->where('gender', $gender);
    }

    // ==========================================================================
    // Helper Methods
    // ==========================================================================

    /**
     * Obtener valores de referencia para una longitud y género específicos.
     * 
     * @param float $length Longitud en cm
     * @param int $gender 1 = Masculino, 2 = Femenino
     * @return self|null
     */
    public static function forLengthAndGender(float $length, int $gender): ?self
    {
        return static::where('length', $length)
            ->where('gender', $gender)
            ->first();
    }

    /**
     * Obtener el valor de referencia más cercano si no existe exactamente.
     * Útil para interpolación.
     * 
     * @param float $length Longitud en cm
     * @param int $gender 1 = Masculino, 2 = Femenino
     * @return self|null
     */
    public static function findNearest(float $length, int $gender): ?self
    {
        return static::where('gender', $gender)
            ->orderByRaw('ABS(length - ?)', [$length])
            ->first();
    }
}


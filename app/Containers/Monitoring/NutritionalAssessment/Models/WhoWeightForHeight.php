<?php

namespace App\Containers\Monitoring\NutritionalAssessment\Models;

use App\Ship\Parents\Models\Model as ParentModel;

/**
 * Modelo para la tabla de referencia WHO: Weight for Height.
 * 
 * Contiene los parámetros LMS y desviaciones estándar precalculadas
 * para calcular z-scores de peso según talla y género.
 * Rango: 2-5 años - Medición de pie
 */
final class WhoWeightForHeight extends ParentModel
{
    /**
     * Nombre de la tabla.
     */
    protected $table = 'who_weight_for_height';

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
        'height',
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
        'height' => 'decimal:2',
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
     * Scope para filtrar por talla en cm.
     */
    public function scopeForHeight($query, float $height)
    {
        return $query->where('height', $height);
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
     * Obtener valores de referencia para una talla y género específicos.
     * 
     * @param float $height Talla en cm
     * @param int $gender 1 = Masculino, 2 = Femenino
     * @return self|null
     */
    public static function forHeightAndGender(float $height, int $gender): ?self
    {
        return static::where('height', $height)
            ->where('gender', $gender)
            ->first();
    }

    /**
     * Obtener el valor de referencia más cercano si no existe exactamente.
     * Útil para interpolación.
     * 
     * @param float $height Talla en cm
     * @param int $gender 1 = Masculino, 2 = Femenino
     * @return self|null
     */
    public static function findNearest(float $height, int $gender): ?self
    {
        return static::where('gender', $gender)
            ->orderByRaw('ABS(height - ?)', [$height])
            ->first();
    }
}


<?php

namespace App\Containers\Monitoring\NutritionalAssessment\Models;

use App\Ship\Parents\Models\Model as ParentModel;

/**
 * Modelo para la tabla de referencia WHO: Length for Age.
 * 
 * Contiene los parámetros LMS y desviaciones estándar precalculadas
 * para calcular z-scores de longitud según edad y género.
 * Rango: 0-24 meses (0-2 años) - Medición acostado
 */
final class WhoLengthForAge extends ParentModel
{
    /**
     * Nombre de la tabla.
     */
    protected $table = 'who_length_for_age';

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
        'month',
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
        'month' => 'integer',
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
     * Scope para filtrar por edad en meses.
     */
    public function scopeForMonth($query, int $month)
    {
        return $query->where('month', $month);
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
     * Obtener valores de referencia para una edad y género específicos.
     * 
     * @param int $month Edad en meses (0-24)
     * @param int $gender 1 = Masculino, 2 = Femenino
     * @return self|null
     */
    public static function forAgeAndGender(int $month, int $gender): ?self
    {
        return static::where('month', $month)
            ->where('gender', $gender)
            ->first();
    }

    /**
     * Obtener el valor de referencia más cercano si no existe exactamente.
     * Útil para interpolación.
     * 
     * @param int $month Edad en meses (0-24)
     * @param int $gender 1 = Masculino, 2 = Femenino
     * @return self|null
     */
    public static function findNearest(int $month, int $gender): ?self
    {
        return static::where('gender', $gender)
            ->orderByRaw('ABS(month - ?)', [$month])
            ->first();
    }
}


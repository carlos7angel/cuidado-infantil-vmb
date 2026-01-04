<?php

namespace App\Containers\Monitoring\Child\Models;

use App\Containers\Monitoring\Child\Enums\DeficitLevel;
use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ChildMedicalRecord extends ParentModel
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'child_id',
        // Seguro
        'has_insurance',
        'insurance_details',
        // Datos físicos
        'weight',
        'height',
        // Alergias
        'has_allergies',
        'allergies_details',
        // Tratamiento médico
        'has_medical_treatment',
        'medical_treatment_details',
        // Tratamiento psicológico
        'has_psychological_treatment',
        'psychological_treatment_details',
        // Déficits
        'has_deficit',
        'deficit_auditory',
        'deficit_visual',
        'deficit_tactile',
        'deficit_motor',
        // Enfermedad
        'has_illness',
        'illness_details',
        // Documentos
        'medical_report_document',
        'diagnosis_document',
        // Otros
        'other_observations',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'has_insurance' => 'boolean',
        'has_allergies' => 'boolean',
        'has_medical_treatment' => 'boolean',
        'has_psychological_treatment' => 'boolean',
        'has_deficit' => 'boolean',
        'has_illness' => 'boolean',
        'weight' => 'decimal:2',
        'height' => 'decimal:2',
        'deficit_auditory' => DeficitLevel::class,
        'deficit_visual' => DeficitLevel::class,
        'deficit_tactile' => DeficitLevel::class,
        'deficit_motor' => DeficitLevel::class,
    ];

    // ==========================================================================
    // Relationships
    // ==========================================================================

    /**
     * Get the child that owns this medical record.
     */
    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class);
    }

    // ==========================================================================
    // Accessors
    // ==========================================================================

    /**
     * Check if child has any deficit.
     */
    public function hasAnyDeficit(): bool
    {
        return $this->deficit_auditory !== null
            || $this->deficit_visual !== null
            || $this->deficit_tactile !== null
            || $this->deficit_motor !== null;
    }
}

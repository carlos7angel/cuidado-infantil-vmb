<?php

namespace App\Containers\Monitoring\NutritionalAssessment\Models;

use App\Containers\AppSection\User\Enums\Gender;
use App\Containers\AppSection\User\Models\User;
use App\Containers\Monitoring\Child\Models\Child;
use App\Containers\Monitoring\NutritionalAssessment\Enums\NutritionalStatus;
use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class NutritionalAssessment extends ParentModel
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'child_id',
        'assessed_by',
        'assessment_date',
        'age_in_months',
        // Mediciones
        'weight',
        'height',
        'head_circumference',
        'arm_circumference',
        // Z-scores
        'z_weight_age',
        'z_height_age',
        'z_weight_height',
        'z_bmi_age',
        // Clasificaciones
        'status_weight_age',
        'status_height_age',
        'status_weight_height',
        'status_bmi_age',
        // Observaciones
        'observations',
        'recommendations',
        'actions_taken',
        'next_assessment_date',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'assessment_date' => 'date',
        'next_assessment_date' => 'date',
        'age_in_months' => 'integer',
        'weight' => 'decimal:2',
        'height' => 'decimal:2',
        'head_circumference' => 'decimal:2',
        'arm_circumference' => 'decimal:2',
        'z_weight_age' => 'decimal:2',
        'z_height_age' => 'decimal:2',
        'z_weight_height' => 'decimal:2',
        'z_bmi_age' => 'decimal:2',
        'status_weight_age' => NutritionalStatus::class,
        'status_height_age' => NutritionalStatus::class,
        'status_weight_height' => NutritionalStatus::class,
        'status_bmi_age' => NutritionalStatus::class,
    ];

    // ==========================================================================
    // Relationships
    // ==========================================================================

    /**
     * Get the child for this assessment.
     */
    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class);
    }

    /**
     * Get the user who performed the assessment.
     */
    public function assessedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assessed_by');
    }

    // ==========================================================================
    // Accessors
    // ==========================================================================

    /**
     * Calculate BMI (Body Mass Index).
     * Formula: weight (kg) / height (m)²
     */
    public function getBmiAttribute(): ?float
    {
        if (!$this->weight || !$this->height) {
            return null;
        }

        $heightInMeters = $this->height / 100;

        return round($this->weight / ($heightInMeters * $heightInMeters), 2);
    }

    /**
     * Get age as human-readable string.
     */
    public function getAgeReadableAttribute(): string
    {
        $months = $this->age_in_months;

        if ($months < 12) {
            return "{$months} " . ($months === 1 ? 'mes' : 'meses');
        }

        $years = intdiv($months, 12);
        $remainingMonths = $months % 12;

        if ($remainingMonths === 0) {
            return "{$years} " . ($years === 1 ? 'año' : 'años');
        }

        // return "{$years}a {$remainingMonths}m";
        return "{$years} " . ($years === 1 ? 'año' : 'años') . " y {$remainingMonths} " . ($remainingMonths === 1 ? 'mes' : 'meses');
    }

    // ==========================================================================
    // Helper Methods
    // ==========================================================================

    /**
     * Check if any indicator requires attention.
     */
    public function requiresAttention(): bool
    {
        $statuses = [
            $this->status_weight_age,
            $this->status_height_age,
            $this->status_weight_height,
            $this->status_bmi_age,
        ];

        foreach ($statuses as $status) {
            if ($status instanceof NutritionalStatus && $status->requiresAttention()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the most critical status among all indicators.
     */
    public function getMostCriticalStatus(): ?NutritionalStatus
    {
        $statuses = array_filter([
            $this->status_weight_age,
            $this->status_height_age,
            $this->status_weight_height,
            $this->status_bmi_age,
        ]);

        if (empty($statuses)) {
            return null;
        }

        // Priority order (most severe first)
        $priority = [
            NutritionalStatus::SEVERE_UNDERWEIGHT,
            NutritionalStatus::VERY_HIGH,
            NutritionalStatus::OBESITY,
            NutritionalStatus::UNDERWEIGHT,
            NutritionalStatus::HIGH,
            NutritionalStatus::OVERWEIGHT,
            NutritionalStatus::UNDERWEIGHT_RISK,
            NutritionalStatus::OVERWEIGHT_RISK,
            NutritionalStatus::NORMAL,
        ];

        foreach ($priority as $priorityStatus) {
            if (in_array($priorityStatus, $statuses, true)) {
                return $priorityStatus;
            }
        }

        return $statuses[0];
    }

    /**
     * Get the label for the most critical status, using the appropriate label method
     * based on which indicator is the most critical.
     * Priority order: Height/Age > Weight/Height > BMI/Age > Weight/Age
     */
    public function getMostCriticalStatusLabel(): ?string
    {
        $criticalStatus = $this->getMostCriticalStatus();
        if (!$criticalStatus) {
            return null;
        }

        // Determine which indicator is the most critical and use its specific label
        // Priority order matches clinical severity: Height issues are most critical
        if ($this->status_height_age === $criticalStatus) {
            return $criticalStatus->labelForHeightForAge();
        }
        if ($this->status_weight_height === $criticalStatus) {
            return $criticalStatus->labelForWeightForHeight();
        }
        if ($this->status_bmi_age === $criticalStatus) {
            return $criticalStatus->labelForBmiForAge();
        }
        if ($this->status_weight_age === $criticalStatus) {
            return $criticalStatus->labelForWeightForAge();
        }

        // Fallback to generic label if status doesn't match any indicator
        return $criticalStatus->label();
    }

    /**
     * Update classification statuses from Z-scores.
     * Usa métodos específicos de interpretación según el tipo de indicador.
     */
    public function updateStatusFromZScores(): void
    {
        if ($this->z_weight_age !== null) {
            $this->status_weight_age = NutritionalStatus::fromWeightForAgeZScore($this->z_weight_age);
        }
        if ($this->z_height_age !== null) {
            $this->status_height_age = NutritionalStatus::fromHeightForAgeZScore($this->z_height_age);
        }
        if ($this->z_weight_height !== null) {
            $this->status_weight_height = NutritionalStatus::fromWeightForHeightZScore($this->z_weight_height);
        }
        if ($this->z_bmi_age !== null) {
            $this->status_bmi_age = NutritionalStatus::fromBmiForAgeZScore($this->z_bmi_age);
        }
    }

    /**
     * Convert Gender enum to WHO format (1 = Masculino, 2 = Femenino).
     */
    private function getWhoGender(): ?int
    {
        $child = $this->child;
        if (!$child || !$child->gender) {
            return null;
        }

        return match ($child->gender) {
            Gender::MALE => 1,
            Gender::FEMALE => 2,
            default => null,
        };
    }

    /**
     * Calculate Z-score using LMS method.
     * Formula: Z = ((X/M)^L - 1) / (L * S)
     * 
     * @param float $observedValue Valor observado (peso, altura, etc.)
     * @param float $L Lambda
     * @param float $M Mu (mediana)
     * @param float $S Sigma
     * @return float|null Z-score calculado
     */
    private function calculateZScore(float $observedValue, float $L, float $M, float $S): ?float
    {
        if ($M <= 0 || $S <= 0) {
            return null;
        }

        // Si L es 0 o muy cercano a 0, usar fórmula simplificada
        if (abs($L) < 0.0001) {
            // Z = ln(X/M) / S
            return log($observedValue / $M) / $S;
        }

        // Fórmula estándar LMS
        $ratio = $observedValue / $M;
        $numerator = (pow($ratio, $L) - 1);
        $denominator = $L * $S;

        if ($denominator == 0) {
            return null;
        }

        return $numerator / $denominator;
    }

    /**
     * Calculate Z-score for Weight/Age.
     */
    public function calculateZWeightAge(): ?float
    {
        if (!$this->weight || !$this->age_in_months) {
            return null;
        }

        $gender = $this->getWhoGender();
        if (!$gender) {
            return null;
        }

        $reference = WhoWeightForAge::forAgeAndGender($this->age_in_months, $gender);
        // Si no se encuentra exacto, buscar el más cercano
        if (!$reference) {
            $reference = WhoWeightForAge::findNearest($this->age_in_months, $gender);
        }

        if (!$reference) {
            return null;
        }

        return $this->calculateZScore($this->weight, $reference->L, $reference->M, $reference->S);
    }

    /**
     * Calculate Z-score for Height/Length/Age.
     * Automatically uses Length (0-24 meses) or Height (24-60 meses) based on age.
     */
    public function calculateZHeightAge(): ?float
    {
        if (!$this->height || !$this->age_in_months) {
            return null;
        }

        $gender = $this->getWhoGender();
        if (!$gender) {
            return null;
        }

        // 0-24 meses: usar Length for Age
        if ($this->age_in_months <= 24) {
            $reference = WhoLengthForAge::forAgeAndGender($this->age_in_months, $gender);
            // Si no se encuentra exacto, buscar el más cercano
            if (!$reference) {
                $reference = WhoLengthForAge::findNearest($this->age_in_months, $gender);
            }
        } else {
            // 24-60 meses: usar Height for Age
            $reference = WhoHeightForAge::forAgeAndGender($this->age_in_months, $gender);
            // Si no se encuentra exacto, buscar el más cercano
            if (!$reference) {
                $reference = WhoHeightForAge::findNearest($this->age_in_months, $gender);
            }
        }

        if (!$reference) {
            return null;
        }

        return $this->calculateZScore($this->height, $reference->L, $reference->M, $reference->S);
    }

    /**
     * Calculate Z-score for Weight/Height/Length.
     * Automatically uses Weight/Length (0-2 años) or Weight/Height (2-5 años) based on age.
     */
    public function calculateZWeightHeight(): ?float
    {
        if (!$this->weight || !$this->height || !$this->age_in_months) {
            return null;
        }

        $gender = $this->getWhoGender();
        if (!$gender) {
            return null;
        }

        // 0-24 meses: usar Weight for Length
        if ($this->age_in_months <= 24) {
            $reference = WhoWeightForLength::forLengthAndGender($this->height, $gender);
            // Si no se encuentra exacto, buscar el más cercano
            if (!$reference) {
                $reference = WhoWeightForLength::findNearest($this->height, $gender);
            }
        } else {
            // 24-60 meses: usar Weight for Height
            $reference = WhoWeightForHeight::forHeightAndGender($this->height, $gender);
            // Si no se encuentra exacto, buscar el más cercano
            if (!$reference) {
                $reference = WhoWeightForHeight::findNearest($this->height, $gender);
            }
        }

        if (!$reference) {
            return null;
        }

        return $this->calculateZScore($this->weight, $reference->L, $reference->M, $reference->S);
    }

    /**
     * Calculate Z-score for BMI/Age.
     * Automatically uses BMI/Length (0-24 meses) or BMI/Height (24-60 meses) based on age.
     */
    public function calculateZBmiAge(): ?float
    {
        $bmi = $this->bmi;
        if (!$bmi || !$this->age_in_months) {
            return null;
        }

        $gender = $this->getWhoGender();
        if (!$gender) {
            return null;
        }

        // 0-24 meses: usar BMI for Age (Length)
        if ($this->age_in_months <= 24) {
            $reference = WhoBmiForAgeLength::forAgeAndGender($this->age_in_months, $gender);
            // Si no se encuentra exacto, buscar el más cercano
            if (!$reference) {
                $reference = WhoBmiForAgeLength::findNearest($this->age_in_months, $gender);
            }
        } else {
            // 24-60 meses: usar BMI for Age (Height)
            $reference = WhoBmiForAgeHeight::forAgeAndGender($this->age_in_months, $gender);
            // Si no se encuentra exacto, buscar el más cercano
            if (!$reference) {
                $reference = WhoBmiForAgeHeight::findNearest($this->age_in_months, $gender);
            }
        }

        if (!$reference) {
            return null;
        }

        return $this->calculateZScore($bmi, $reference->L, $reference->M, $reference->S);
    }

    /**
     * Calculate all Z-scores and update statuses automatically.
     */
    public function calculateAllZScores(): void
    {
        $this->z_weight_age = $this->calculateZWeightAge();
        $this->z_height_age = $this->calculateZHeightAge();
        $this->z_weight_height = $this->calculateZWeightHeight();
        $this->z_bmi_age = $this->calculateZBmiAge();

        // Actualizar clasificaciones basadas en los z-scores calculados
        $this->updateStatusFromZScores();
    }

    /**
     * Get summary of nutritional status.
     */
    public function getSummary(): array
    {
        return [
            'date' => $this->assessment_date->format('d/m/Y'),
            'age' => $this->age_readable,
            'measurements' => [
                'weight' => "{$this->weight} kg",
                'height' => "{$this->height} cm",
                'bmi' => $this->bmi ? "{$this->bmi} kg/m²" : null,
            ],
            'indicators' => [
                'weight_age' => $this->status_weight_age?->label(),
                'height_age' => $this->status_height_age?->label(),
                'weight_height' => $this->status_weight_height?->label(),
                'bmi_age' => $this->status_bmi_age?->label(),
            ],
            'requires_attention' => $this->requiresAttention(),
            'critical_status' => $this->getMostCriticalStatus()?->label(),
        ];
    }
}

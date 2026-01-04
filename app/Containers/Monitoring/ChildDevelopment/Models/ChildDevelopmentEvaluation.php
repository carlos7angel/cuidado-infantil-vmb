<?php

namespace App\Containers\Monitoring\ChildDevelopment\Models;

use App\Containers\AppSection\User\Models\User;
use App\Containers\Monitoring\Child\Models\Child;
use App\Containers\Monitoring\ChildDevelopment\Enums\DevelopmentArea;
use App\Containers\Monitoring\ChildDevelopment\Enums\DevelopmentStatus;
use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class ChildDevelopmentEvaluation extends ParentModel
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'child_id',
        'assessed_by',
        'evaluation_date',
        'age_months',
        'weight',
        'height',
        'notes',
        'next_evaluation_date',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'evaluation_date' => 'date',
        'next_evaluation_date' => 'date',
        'age_months' => 'integer',
        'weight' => 'decimal:2',
        'height' => 'decimal:2',
    ];

    // ==========================================================================
    // Relationships
    // ==========================================================================

    /**
     * Get the child for this evaluation.
     */
    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class);
    }

    /**
     * Get the user who performed the evaluation.
     */
    public function assessedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assessed_by');
    }

    /**
     * Get the evaluation items (results for each item).
     */
    public function evaluationItems(): HasMany
    {
        return $this->hasMany(ChildDevelopmentEvaluationItem::class, 'evaluation_id');
    }

    /**
     * Get the evaluation scores (scores by area).
     */
    public function scores(): HasMany
    {
        return $this->hasMany(ChildDevelopmentEvaluationScore::class, 'evaluation_id');
    }

    // ==========================================================================
    // Accessors
    // ==========================================================================

    /**
     * Get age as human-readable string.
     */
    public function getAgeReadableAttribute(): string
    {
        $months = $this->age_months;

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
     * Calculate raw score for a specific area.
     * Sum of all achieved items for that area (accumulated up to evaluation age).
     */
    public function calculateRawScore(DevelopmentArea $area): int
    {
        // Contar solo los items logrados que son acumulados hasta la edad de evaluación
        return $this->evaluationItems()
            ->whereHas('developmentItem', function ($query) use ($area) {
                $query->where('area', $area->value)
                    ->where('age_max_months', '<=', $this->age_months);
            })
            ->where('achieved', true)
            ->count();
    }

    /**
     * Get or calculate score for a specific area.
     */
    public function getScoreForArea(DevelopmentArea $area): ?ChildDevelopmentEvaluationScore
    {
        return $this->scores()->where('area', $area->value)->first();
    }

    /**
     * Calculate and save scores for all areas.
     */
    public function calculateAndSaveScores(): void
    {
        foreach (DevelopmentArea::cases() as $area) {
            $rawScore = $this->calculateRawScore($area);
            $status = DevelopmentNorm::getStatusForScore($area, $this->age_months, $rawScore);

            $this->scores()->updateOrCreate(
                [
                    'evaluation_id' => $this->id,
                    'area' => $area->value,
                ],
                [
                    'raw_score' => $rawScore,
                    'status' => $status?->value,
                ]
            );
        }
    }

    /**
     * Check if evaluation requires attention (any area in alert status).
     */
    public function requiresAttention(): bool
    {
        return $this->scores()
            ->where('status', DevelopmentStatus::ALERT->value)
            ->exists();
    }

    /**
     * Get areas that require attention.
     * 
     * @return \Illuminate\Support\Collection Collection de DevelopmentArea
     */
    public function getAreasRequiringAttention(): \Illuminate\Support\Collection
    {
        return $this->scores()
            ->where('status', DevelopmentStatus::ALERT->value)
            ->get()
            ->map(fn ($score) => $score->area);
    }

    /**
     * Get overall score (promedio de todos los porcentajes por área).
     * Calcula el promedio de los porcentajes de todas las áreas.
     * 
     * @return float|null Porcentaje overall (0-100) o null si no hay scores
     */
    public function getOverallScore(): ?float
    {
        $scores = $this->scores;
        
        if ($scores->isEmpty()) {
            return null;
        }

        $ageMonths = $this->age_months;
        if ($ageMonths === null) {
            return null;
        }

        $totalPercentage = 0;
        $count = 0;

        foreach ($scores as $score) {
            $maxScore = $score->getMaxPossibleScore($ageMonths);
            if ($maxScore > 0) {
                $percentage = round(($score->raw_score / $maxScore) * 100, 2);
                $totalPercentage += $percentage;
                $count++;
            }
        }

        return $count > 0 ? round($totalPercentage / $count, 2) : null;
    }

    /**
     * Get overall status based on all area scores.
     * 
     * @return string 'normal'|'review'|'alert'
     */
    public function getOverallStatus(): string
    {
        if ($this->requiresAttention()) {
            return 'alert';
        }

        $scores = $this->scores;
        $allNormal = $scores->every(fn ($score) => $score->isNormal());

        return $allNormal ? 'normal' : 'review';
    }

    /**
     * Get summary of the evaluation.
     */
    public function getSummary(): array
    {
        $scores = $this->scores()->get()->keyBy('area');

        return [
            'id' => $this->id,
            'date' => $this->evaluation_date->format('d/m/Y'),
            'age' => $this->age_readable,
            'age_months' => $this->age_months,
            'measurements' => [
                'weight' => $this->weight ? "{$this->weight} kg" : null,
                'height' => $this->height ? "{$this->height} cm" : null,
            ],
            'scores' => [
                'MG' => [
                    'raw_score' => $scores->get(DevelopmentArea::MOTOR_GROSS->value)?->raw_score,
                    'status' => $scores->get(DevelopmentArea::MOTOR_GROSS->value)?->status?->label(),
                ],
                'MF' => [
                    'raw_score' => $scores->get(DevelopmentArea::MOTOR_FINE->value)?->raw_score,
                    'status' => $scores->get(DevelopmentArea::MOTOR_FINE->value)?->status?->label(),
                ],
                'AL' => [
                    'raw_score' => $scores->get(DevelopmentArea::LANGUAGE->value)?->raw_score,
                    'status' => $scores->get(DevelopmentArea::LANGUAGE->value)?->status?->label(),
                ],
                'PS' => [
                    'raw_score' => $scores->get(DevelopmentArea::PERSONAL_SOCIAL->value)?->raw_score,
                    'status' => $scores->get(DevelopmentArea::PERSONAL_SOCIAL->value)?->status?->label(),
                ],
            ],
            'overall_score' => $this->getOverallScore(),
            'overall_status' => $this->getOverallStatus(),
            'requires_attention' => $this->requiresAttention(),
            'areas_requiring_attention' => $this->getAreasRequiringAttention()
                ->map(fn ($area) => $area->label())
                ->toArray(),
        ];
    }

    /**
     * Get all accumulated items up to this evaluation's age.
     * Returns all items that the child should have achieved up to their current age.
     */
    public function getApplicableItems(): \Illuminate\Database\Eloquent\Collection
    {
        return DevelopmentItem::where('age_max_months', '<=', $this->age_months)
            ->orderBy('area')
            ->orderBy('item_number')
            ->get();
    }

    /**
     * Get complete items history with mapping.
     * Reconstruye el historial completo mapeando todos los items acumulados
     * con los items guardados en la evaluación (achieved=true).
     * Los items no guardados se consideran achieved=false.
     * 
     * @return \Illuminate\Support\Collection Collection de items completos con achieved
     */
    public function getCompleteItemsHistory(): \Illuminate\Support\Collection
    {
        // Obtener todos los items acumulados hasta la edad de evaluación
        $allAccumulatedItems = DevelopmentItem::getAccumulatedUpToAge($this->age_months);
        
        // Obtener los items guardados en esta evaluación (solo los logrados)
        $evaluationItems = $this->evaluationItems()
            ->where('achieved', true)
            ->with('developmentItem')
            ->get()
            ->keyBy('development_item_id');
        
        // Mapear: si el item está guardado, entonces achieved=true, sino false
        return $allAccumulatedItems->map(function ($item) use ($evaluationItems) {
            $evaluationItem = $evaluationItems->get($item->id);
            
            return (object) [
                'development_item' => $item,
                'achieved' => $evaluationItem !== null,
                'evaluation_item_id' => $evaluationItem?->id,
            ];
        });
    }
}


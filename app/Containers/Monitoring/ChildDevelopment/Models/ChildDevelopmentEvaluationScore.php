<?php

namespace App\Containers\Monitoring\ChildDevelopment\Models;

use App\Containers\Monitoring\ChildDevelopment\Enums\DevelopmentArea;
use App\Containers\Monitoring\ChildDevelopment\Enums\DevelopmentStatus;
use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ChildDevelopmentEvaluationScore extends ParentModel
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'evaluation_id',
        'area',
        'raw_score',
        'status',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'raw_score' => 'integer',
        'area' => DevelopmentArea::class,
        'status' => DevelopmentStatus::class,
    ];

    // ==========================================================================
    // Relationships
    // ==========================================================================

    /**
     * Get the evaluation for this score.
     */
    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(ChildDevelopmentEvaluation::class, 'evaluation_id');
    }

    // ==========================================================================
    // Accessors
    // ==========================================================================

    /**
     * Get the area label.
     */
    public function getAreaLabelAttribute(): string
    {
        return $this->area->label();
    }

    /**
     * Get the status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->status->label();
    }

    /**
     * Get the status description.
     */
    public function getStatusDescriptionAttribute(): string
    {
        return $this->status->description();
    }

    /**
     * Get the status color for UI.
     */
    public function getStatusColorAttribute(): string
    {
        return $this->status->color();
    }

    // ==========================================================================
    // Helper Methods
    // ==========================================================================

    /**
     * Check if this score requires attention.
     */
    public function requiresAttention(): bool
    {
        return $this->status->requiresAttention();
    }

    /**
     * Check if this score is within normal range.
     */
    public function isNormal(): bool
    {
        return $this->status->isNormal();
    }

    /**
     * Get the maximum possible score for this area and age.
     * Returns the total number of accumulated items up to the evaluation age.
     * 
     * @param int|null $ageMonths Edad en meses. Si no se proporciona, se obtiene de la relación evaluation.
     */
    public function getMaxPossibleScore(?int $ageMonths = null): int
    {
        // Si no se proporciona ageMonths, intentar obtenerlo de la relación (si está cargada)
        if ($ageMonths === null) {
            // Verificar si la relación está cargada antes de acceder
            if (!$this->relationLoaded('evaluation') && !$this->evaluation_id) {
                return 0;
            }
            
            // Si la relación no está cargada pero tenemos evaluation_id, necesitamos cargarla
            // o mejor, evitar el lazy loading y requerir que se pase ageMonths
            if (!$this->relationLoaded('evaluation')) {
                // Intentar cargar la relación solo si es necesario
                $this->load('evaluation');
            }
            
            $evaluation = $this->evaluation;
            if (!$evaluation) {
                return 0;
            }
            
            $ageMonths = $evaluation->age_months;
        }

        return DevelopmentItem::accumulatedForAreaUpToAge($this->area, $ageMonths)
            ->count();
    }

    /**
     * Get the percentage score.
     * Nota: Este accessor puede causar lazy loading. 
     * Preferir calcular el porcentaje directamente pasando ageMonths.
     * 
     * @param int|null $ageMonths Edad en meses para calcular el porcentaje
     */
    public function getPercentage(?int $ageMonths = null): ?float
    {
        $maxScore = $this->getMaxPossibleScore($ageMonths);
        if ($maxScore === 0) {
            return null;
        }

        return round(($this->raw_score / $maxScore) * 100, 2);
    }

    /**
     * Get the percentage score (accessor).
     * @deprecated Usar getPercentage($ageMonths) para evitar lazy loading
     */
    protected function getPercentageAttribute(): ?float
    {
        // Intentar obtener ageMonths de la evaluación si está cargada
        if ($this->relationLoaded('evaluation') && $this->evaluation) {
            return $this->getPercentage($this->evaluation->age_months);
        }
        
        // Si no está cargada, retornar null para evitar lazy loading
        return null;
    }

    /**
     * Get formatted score information.
     */
    public function getFormattedScore(): array
    {
        $maxScore = $this->getMaxPossibleScore();

        return [
            'area' => $this->area->value,
            'area_label' => $this->area->label(),
            'raw_score' => $this->raw_score,
            'max_score' => $maxScore,
            'percentage' => $this->percentage,
            'status' => $this->status->value,
            'status_label' => $this->status->label(),
            'status_description' => $this->status->description(),
            'status_color' => $this->status->color(),
            'requires_attention' => $this->requiresAttention(),
        ];
    }
}


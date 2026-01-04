<?php

namespace App\Containers\Monitoring\ChildDevelopment\Models;

use App\Containers\Monitoring\ChildDevelopment\Enums\DevelopmentArea;
use App\Containers\Monitoring\ChildDevelopment\Enums\DevelopmentStatus;
use App\Ship\Parents\Models\Model as ParentModel;

final class DevelopmentNorm extends ParentModel
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'area',
        'age_min_months',
        'age_max_months',
        'min_score',
        'max_score',
        'status',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'age_min_months' => 'integer',
        'age_max_months' => 'integer',
        'min_score' => 'integer',
        'max_score' => 'integer',
        'area' => DevelopmentArea::class,
        'status' => DevelopmentStatus::class,
    ];

    // ==========================================================================
    // Scopes
    // ==========================================================================

    /**
     * Scope to filter norms by area.
     */
    public function scopeByArea($query, DevelopmentArea $area)
    {
        return $query->where('area', $area->value);
    }

    /**
     * Scope to filter norms by age range.
     */
    public function scopeForAge($query, int $ageMonths)
    {
        return $query->where('age_min_months', '<=', $ageMonths)
            ->where('age_max_months', '>=', $ageMonths);
    }

    /**
     * Scope to filter norms by area and age.
     */
    public function scopeForAreaAndAge($query, DevelopmentArea $area, int $ageMonths)
    {
        return $query->byArea($area)->forAge($ageMonths);
    }

    /**
     * Scope to filter norms by score range.
     */
    public function scopeForScore($query, int $score)
    {
        return $query->where('min_score', '<=', $score)
            ->where('max_score', '>=', $score);
    }

    // ==========================================================================
    // Helper Methods
    // ==========================================================================

    /**
     * Check if this norm is applicable for a given age.
     */
    public function isApplicableForAge(int $ageMonths): bool
    {
        return $ageMonths >= $this->age_min_months && $ageMonths <= $this->age_max_months;
    }

    /**
     * Check if this norm applies to a given score.
     */
    public function appliesToScore(int $score): bool
    {
        return $score >= $this->min_score && $score <= $this->max_score;
    }

    /**
     * Get the status for a given score, area, and age.
     * 
     * @return DevelopmentStatus|null
     */
    public static function getStatusForScore(DevelopmentArea $area, int $ageMonths, int $score): ?DevelopmentStatus
    {
        $norm = static::forAreaAndAge($area, $ageMonths)
            ->forScore($score)
            ->first();

        return $norm?->status;
    }

    /**
     * Get all norms for a specific area and age, ordered by status priority.
     */
    public static function getNormsForAreaAndAge(DevelopmentArea $area, int $ageMonths): \Illuminate\Database\Eloquent\Collection
    {
        return static::forAreaAndAge($area, $ageMonths)
            ->orderBy('min_score')
            ->get();
    }
}


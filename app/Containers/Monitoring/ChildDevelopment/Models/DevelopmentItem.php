<?php

namespace App\Containers\Monitoring\ChildDevelopment\Models;

use App\Containers\Monitoring\ChildDevelopment\Enums\DevelopmentArea;
use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class DevelopmentItem extends ParentModel
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'item_number',
        'age_min_months',
        'age_max_months',
        'area',
        'description',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'item_number' => 'integer',
        'age_min_months' => 'integer',
        'age_max_months' => 'integer',
        'area' => DevelopmentArea::class,
    ];

    // ==========================================================================
    // Relationships
    // ==========================================================================

    /**
     * Get the evaluation items for this development item.
     */
    public function evaluationItems(): HasMany
    {
        return $this->hasMany(ChildDevelopmentEvaluationItem::class, 'development_item_id');
    }

    // ==========================================================================
    // Scopes
    // ==========================================================================

    /**
     * Scope to filter items by area.
     */
    public function scopeByArea($query, DevelopmentArea $area)
    {
        return $query->where('area', $area->value);
    }

    /**
     * Scope to filter items by age range (items that apply exactly for this age).
     */
    public function scopeForAge($query, int $ageMonths)
    {
        return $query->where('age_min_months', '<=', $ageMonths)
            ->where('age_max_months', '>=', $ageMonths);
    }

    /**
     * Scope to filter accumulated items up to a given age.
     * Returns all items that should have been achieved up to this age.
     */
    public function scopeAccumulatedUpToAge($query, int $ageMonths)
    {
        return $query->where('age_max_months', '<=', $ageMonths);
    }

    /**
     * Scope to filter items by area and age.
     */
    public function scopeForAreaAndAge($query, DevelopmentArea $area, int $ageMonths)
    {
        return $query->byArea($area)->forAge($ageMonths);
    }

    /**
     * Scope to filter accumulated items by area up to a given age.
     */
    public function scopeAccumulatedForAreaUpToAge($query, DevelopmentArea $area, int $ageMonths)
    {
        return $query->byArea($area)->accumulatedUpToAge($ageMonths);
    }

    // ==========================================================================
    // Helper Methods
    // ==========================================================================

    /**
     * Check if this item is applicable for a given age (exact match).
     */
    public function isApplicableForAge(int $ageMonths): bool
    {
        return $ageMonths >= $this->age_min_months && $ageMonths <= $this->age_max_months;
    }

    /**
     * Check if this item should have been achieved up to a given age (accumulated).
     */
    public function isAccumulatedUpToAge(int $ageMonths): bool
    {
        return $this->age_max_months <= $ageMonths;
    }

    /**
     * Get items for a specific area and age (exact match).
     */
    public static function getForAreaAndAge(DevelopmentArea $area, int $ageMonths): \Illuminate\Database\Eloquent\Collection
    {
        return static::forAreaAndAge($area, $ageMonths)
            ->orderBy('item_number')
            ->get();
    }

    /**
     * Get all accumulated items up to a given age (all items that should have been achieved).
     */
    public static function getAccumulatedUpToAge(int $ageMonths): \Illuminate\Database\Eloquent\Collection
    {
        return static::accumulatedUpToAge($ageMonths)
            ->orderBy('area')
            ->orderBy('item_number')
            ->get();
    }

    /**
     * Get accumulated items for a specific area up to a given age.
     */
    public static function getAccumulatedForAreaUpToAge(DevelopmentArea $area, int $ageMonths): \Illuminate\Database\Eloquent\Collection
    {
        return static::accumulatedForAreaUpToAge($area, $ageMonths)
            ->orderBy('item_number')
            ->get();
    }
}


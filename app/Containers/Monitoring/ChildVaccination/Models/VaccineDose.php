<?php

namespace App\Containers\Monitoring\ChildVaccination\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class VaccineDose extends ParentModel
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'vaccine_id',
        'dose_number',
        'recommended_age_months',
        'min_age_months',
        'max_age_months',
        'description',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'dose_number' => 'integer',
        'recommended_age_months' => 'integer',
        'min_age_months' => 'integer',
        'max_age_months' => 'integer',
    ];

    // ==========================================================================
    // Relationships
    // ==========================================================================

    /**
     * Get the vaccine for this dose.
     */
    public function vaccine(): BelongsTo
    {
        return $this->belongsTo(Vaccine::class);
    }

    /**
     * Get the child vaccinations for this dose.
     */
    public function childVaccinations(): HasMany
    {
        return $this->hasMany(ChildVaccination::class);
    }

    // ==========================================================================
    // Age Status Methods
    // ==========================================================================

    /**
     * Check if child is within the valid age range for this dose.
     */
    public function isWithinValidAge(int $childAgeMonths): bool
    {
        if ($childAgeMonths < $this->min_age_months) {
            return false;
        }

        if ($this->max_age_months !== null && $childAgeMonths > $this->max_age_months) {
            return false;
        }

        return true;
    }

    /**
     * Check if child is too young for this dose.
     */
    public function isTooYoung(int $childAgeMonths): bool
    {
        return $childAgeMonths < $this->min_age_months;
    }

    /**
     * Check if child has exceeded max age for this dose.
     */
    public function isExpired(int $childAgeMonths): bool
    {
        return $this->max_age_months !== null && $childAgeMonths > $this->max_age_months;
    }

    /**
     * Check if this dose is overdue (past recommended but still valid).
     */
    public function isOverdue(int $childAgeMonths): bool
    {
        return $childAgeMonths > $this->recommended_age_months && $this->isWithinValidAge($childAgeMonths);
    }

    /**
     * Check if child is at the ideal age for this dose.
     */
    public function isAtIdealAge(int $childAgeMonths): bool
    {
        return $childAgeMonths >= $this->min_age_months && $childAgeMonths <= $this->recommended_age_months;
    }

    /**
     * Get the status of this dose for a given child age.
     *
     * @return string 'pending'|'ideal'|'overdue'|'expired'|'too_young'
     */
    public function getStatusForAge(int $childAgeMonths): string
    {
        if ($this->isTooYoung($childAgeMonths)) {
            return 'too_young'; // Aún no tiene edad
        }

        if ($this->isExpired($childAgeMonths)) {
            return 'expired'; // Ya pasó la edad máxima
        }

        if ($this->isAtIdealAge($childAgeMonths)) {
            return 'ideal'; // Está en la edad ideal
        }

        if ($this->isOverdue($childAgeMonths)) {
            return 'overdue'; // Atrasada pero aún puede recibirla
        }

        return 'pending';
    }

    // ==========================================================================
    // Accessors
    // ==========================================================================

    /**
     * Convert months to human-readable string.
     */
    private function monthsToReadable(int $months): string
    {
        if ($months === 0) {
            return 'al nacer';
        }

        if ($months < 12) {
            return "{$months} " . ($months === 1 ? 'mes' : 'meses');
        }

        $years = intdiv($months, 12);
        $remainingMonths = $months % 12;

        if ($remainingMonths === 0) {
            return "{$years} " . ($years === 1 ? 'año' : 'años');
        }

        return "{$years} " . ($years === 1 ? 'año' : 'años') . " y {$remainingMonths} " . ($remainingMonths === 1 ? 'mes' : 'meses');
    }

    /**
     * Get the recommended age as a human-readable string.
     */
    public function getRecommendedAgeAttribute(): string
    {
        return $this->monthsToReadable($this->recommended_age_months);
    }

    /**
     * Get the age range as a human-readable string.
     */
    public function getAgeRangeAttribute(): string
    {
        $min = $this->monthsToReadable($this->min_age_months);
        $max = $this->max_age_months !== null
            ? $this->monthsToReadable($this->max_age_months)
            : 'sin límite';

        return "{$min} - {$max}";
    }
}

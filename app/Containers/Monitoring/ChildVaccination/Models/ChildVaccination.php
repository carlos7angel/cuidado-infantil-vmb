<?php

namespace App\Containers\Monitoring\ChildVaccination\Models;

use App\Containers\Monitoring\Child\Models\Child;
use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ChildVaccination extends ParentModel
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'child_id',
        'vaccine_dose_id',
        'date_applied',
        'applied_at',
        'notes',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'date_applied' => 'date',
    ];

    // ==========================================================================
    // Relationships
    // ==========================================================================

    /**
     * Get the child for this vaccination.
     */
    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class);
    }

    /**
     * Get the vaccine dose for this vaccination.
     */
    public function vaccineDose(): BelongsTo
    {
        return $this->belongsTo(VaccineDose::class);
    }

    // ==========================================================================
    // Accessors
    // ==========================================================================

    /**
     * Get the vaccine name directly.
     */
    public function getVaccineNameAttribute(): string
    {
        return $this->vaccineDose->vaccine->name;
    }

    /**
     * Get the dose number directly.
     */
    public function getDoseNumberAttribute(): int
    {
        return $this->vaccineDose->dose_number;
    }
}

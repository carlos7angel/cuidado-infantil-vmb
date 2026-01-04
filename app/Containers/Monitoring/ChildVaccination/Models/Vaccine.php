<?php

namespace App\Containers\Monitoring\ChildVaccination\Models;

use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Vaccine extends ParentModel
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
        'total_doses',
        'is_required',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'total_doses' => 'integer',
        'is_required' => 'boolean',
    ];

    // ==========================================================================
    // Relationships
    // ==========================================================================

    /**
     * Get the doses for this vaccine.
     */
    public function doses(): HasMany
    {
        return $this->hasMany(VaccineDose::class)->orderBy('dose_number');
    }

    /**
     * Get the child vaccinations for this vaccine (through doses).
     */
    public function childVaccinations(): HasMany
    {
        return $this->hasManyThrough(
            ChildVaccination::class,
            VaccineDose::class,
            'vaccine_id',
            'vaccine_dose_id'
        );
    }
}

<?php

namespace App\Containers\Monitoring\Child\Models;

use App\Containers\Monitoring\Child\Enums\GuardianType;
use App\Containers\Monitoring\Child\Enums\HousingTenure;
use App\Containers\Monitoring\Child\Enums\HousingType;
use App\Containers\Monitoring\Child\Enums\TransportType;
use App\Containers\Monitoring\Child\Enums\TravelTime;
use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class ChildSocialRecord extends ParentModel
{
    protected $table = 'child_social_records';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'child_id',
        'guardian_type',
        // Egresos
        'expense_food',
        'expense_education',
        'expense_housing',
        'expense_transport',
        'expense_clothing',
        'expense_utilities',
        'expense_health',
        'expense_debts',
        'expense_debts_detail',
        // Habitabilidad
        'housing_type',
        'housing_tenure',
        'housing_wall_material',
        'housing_floor_material',
        'housing_finish',
        'housing_bedrooms',
        'housing_rooms',
        'housing_utilities',
        // Transporte
        'transport_type',
        'travel_time',
        // Croquis
        'home_sketch',
        'work_sketch',
        // Historia
        'family_history',
        'professional_assessment',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'guardian_type' => GuardianType::class,
        'housing_type' => HousingType::class,
        'housing_tenure' => HousingTenure::class,
        'transport_type' => TransportType::class,
        'travel_time' => TravelTime::class,
        'housing_rooms' => 'array',
        'housing_utilities' => 'array',
        'expense_food' => 'decimal:2',
        'expense_education' => 'decimal:2',
        'expense_housing' => 'decimal:2',
        'expense_transport' => 'decimal:2',
        'expense_clothing' => 'decimal:2',
        'expense_utilities' => 'decimal:2',
        'expense_health' => 'decimal:2',
        'expense_debts' => 'decimal:2',
    ];

    // ==========================================================================
    // Relationships
    // ==========================================================================

    /**
     * Get the child that owns this social record.
     */
    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class);
    }

    /**
     * Get the family members for this social record.
     */
    public function familyMembers(): HasMany
    {
        return $this->hasMany(ChildFamilyMember::class);
    }

    // ==========================================================================
    // Accessors
    // ==========================================================================

    /**
     * Get total expenses.
     */
    public function getTotalExpensesAttribute(): float
    {
        return (float) $this->expense_food
            + (float) $this->expense_education
            + (float) $this->expense_housing
            + (float) $this->expense_transport
            + (float) $this->expense_clothing
            + (float) $this->expense_utilities
            + (float) $this->expense_health
            + (float) $this->expense_debts;
    }
}

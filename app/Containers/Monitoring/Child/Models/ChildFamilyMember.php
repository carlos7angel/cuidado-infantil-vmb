<?php

namespace App\Containers\Monitoring\Child\Models;

use App\Containers\AppSection\User\Enums\Gender;
use App\Containers\Monitoring\Child\Enums\IncomeType;
use App\Containers\Monitoring\Child\Enums\Kinship;
use App\Containers\Monitoring\Child\Enums\MaritalStatus;
use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ChildFamilyMember extends ParentModel
{
    protected $table = 'child_family_members';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'child_social_record_id',
        'first_name',
        'last_name',
        'birth_date',
        'gender',
        'kinship',
        'education_level',
        'profession',
        'marital_status',
        'phone',
        'has_income',
        'workplace',
        'income_type',
        'total_income',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'birth_date' => 'date',
        'gender' => Gender::class,
        'kinship' => Kinship::class,
        'marital_status' => MaritalStatus::class,
        'income_type' => IncomeType::class,
        'has_income' => 'boolean',
        'total_income' => 'decimal:2',
    ];

    // ==========================================================================
    // Relationships
    // ==========================================================================

    /**
     * Get the child social record that owns this family member.
     */
    public function socialRecord(): BelongsTo
    {
        return $this->belongsTo(ChildSocialRecord::class, 'child_social_record_id');
    }

    // ==========================================================================
    // Accessors
    // ==========================================================================

    /**
     * Get the family member's full name.
     */
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn (): string => "{$this->first_name} {$this->last_name}",
        );
    }

    /**
     * Get the family member's age.
     */
    protected function age(): Attribute
    {
        return Attribute::make(
            get: fn (): ?int => $this->birth_date?->age,
        );
    }
}

<?php

namespace App\Containers\Monitoring\Educator\Models;

use App\Containers\AppSection\User\Enums\Gender;
use App\Containers\AppSection\User\Models\User;
use App\Containers\Monitoring\ChildcareCenter\Models\ChildcareCenter;
use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

final class Educator extends ParentModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'gender',
        'birth',
        'state',
        'dni',
        'phone',
        'contract_start_date',
        'contract_end_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birth' => 'date',
        'contract_start_date' => 'date',
        'contract_end_date' => 'date',
        'gender' => Gender::class,
    ];

    // ==========================================================================
    // Accessors
    // ==========================================================================

    /**
     * Get the educator's full name.
     */
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn (): string => "{$this->first_name} {$this->last_name}",
        );
    }

    // ==========================================================================
    // Mutators
    // ==========================================================================

    /**
     * Set the contract start date.
     * Converts from d/m/Y format to Y-m-d format for database storage.
     */
    protected function contractStartDate(): Attribute
    {
        return Attribute::make(
            set: function (string|null $value): string|null {
                if (empty($value)) {
                    return null;
                }

                try {
                    $date = \DateTime::createFromFormat('d/m/Y', $value);
                    if ($date) {
                        return $date->format('Y-m-d');
                    }
                } catch (\Exception $e) {
                    // Fallback to strtotime if DateTime fails
                    return date('Y-m-d', strtotime($value));
                }

                return $value;
            }
        );
    }

    /**
     * Set the contract end date.
     * Converts from d/m/Y format to Y-m-d format for database storage.
     */
    protected function contractEndDate(): Attribute
    {
        return Attribute::make(
            set: function (string|null $value): string|null {
                if (empty($value)) {
                    return null;
                }

                try {
                    $date = \DateTime::createFromFormat('d/m/Y', $value);
                    if ($date) {
                        return $date->format('Y-m-d');
                    }
                } catch (\Exception $e) {
                    // Fallback to strtotime if DateTime fails
                    return date('Y-m-d', strtotime($value));
                }

                return $value;
            }
        );
    }

    // ==========================================================================
    // Relationships
    // ==========================================================================

    /**
     * Get the user account for this educator.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the childcare centers where this educator is assigned.
     */
    public function childcareCenters(): BelongsToMany
    {
        return $this->belongsToMany(ChildcareCenter::class)
            ->withPivot(['assigned_at']);
    }

    /**
     * Get only active/current childcare center assignments.
     */
    public function activeChildcareCenters(): BelongsToMany
    {
        return $this->belongsToMany(ChildcareCenter::class)
            ->withPivot(['assigned_at'])
            ->where('is_active', true);
    }
}

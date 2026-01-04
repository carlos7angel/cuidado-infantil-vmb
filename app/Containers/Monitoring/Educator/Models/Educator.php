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
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birth' => 'date',
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

<?php

namespace App\Containers\Monitoring\Room\Models;

use App\Containers\Monitoring\ChildcareCenter\Models\ChildcareCenter;
use App\Containers\Monitoring\ChildEnrollment\Models\ChildEnrollment;
use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Room extends ParentModel
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'childcare_center_id',
        'name',
        'description',
        'capacity',
        'is_active',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'capacity' => 'integer',
        'is_active' => 'boolean',
    ];

    // ==========================================================================
    // Relationships
    // ==========================================================================

    /**
     * Get the childcare center that owns this room.
     */
    public function childcareCenter(): BelongsTo
    {
        return $this->belongsTo(ChildcareCenter::class);
    }

    /**
     * Get the enrollments assigned to this room.
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(ChildEnrollment::class);
    }

    /**
     * Get the active enrollments assigned to this room.
     */
    public function activeEnrollments(): HasMany
    {
        return $this->hasMany(ChildEnrollment::class)->where('status', 'activo');
    }

    // ==========================================================================
    // Helpers
    // ==========================================================================

    /**
     * Get the current number of children in this room.
     */
    public function getCurrentChildrenCount(): int
    {
        return $this->activeEnrollments()->count();
    }

    /**
     * Check if the room has available capacity.
     */
    public function hasAvailableCapacity(): bool
    {
        return $this->getCurrentChildrenCount() < $this->capacity;
    }

    /**
     * Get available spots in the room.
     */
    public function getAvailableSpots(): int
    {
        return max(0, $this->capacity - $this->getCurrentChildrenCount());
    }
}

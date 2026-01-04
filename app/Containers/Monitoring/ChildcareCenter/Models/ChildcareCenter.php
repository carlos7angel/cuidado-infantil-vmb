<?php

namespace App\Containers\Monitoring\ChildcareCenter\Models;

use App\Containers\Monitoring\Educator\Models\Educator;
use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class ChildcareCenter extends ParentModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
        'type',
        'date_founded',
        'address',
        'phone',
        'email',
        'social_media',
        'logo',
        'state',
        'city',
        'municipality',
        'contact_name',
        'contact_phone',
        'contact_email',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_founded' => 'date',
    ];

    // ==========================================================================
    // Relationships
    // ==========================================================================

    /**
     * Get the educators assigned to this childcare center.
     */
    public function educators(): BelongsToMany
    {
        return $this->belongsToMany(Educator::class)
            ->withPivot(['assigned_at']);
    }

    /**
     * Get the enrollments for this childcare center.
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(\App\Containers\Monitoring\ChildEnrollment\Models\ChildEnrollment::class);
    }

    /**
     * Get the active enrollments for this childcare center.
     */
    public function activeEnrollments(): HasMany
    {
        return $this->hasMany(\App\Containers\Monitoring\ChildEnrollment\Models\ChildEnrollment::class)
            ->where('status', 'activo');
    }

    /**
     * Get the rooms for the childcare center.
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(\App\Containers\Monitoring\Room\Models\Room::class);
    }

    /**
     * Get the active rooms for the childcare center.
     */
    public function activeRooms(): HasMany
    {
        return $this->hasMany(\App\Containers\Monitoring\Room\Models\Room::class)
            ->where('is_active', true);
    }
}

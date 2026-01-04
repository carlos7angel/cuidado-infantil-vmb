<?php

namespace App\Containers\Monitoring\Attendance\Models;

use App\Containers\Monitoring\Attendance\Enums\AttendanceStatus;
use App\Containers\Monitoring\Child\Models\Child;
use App\Containers\Monitoring\ChildcareCenter\Models\ChildcareCenter;
use App\Containers\AppSection\User\Models\User;
use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Attendance extends ParentModel
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'child_id',
        'childcare_center_id',
        'registered_by',
        'date',
        'check_in_time',
        'check_out_time',
        'status',
        'observations',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        'check_in_time' => 'datetime:H:i',
        'check_out_time' => 'datetime:H:i',
        'status' => AttendanceStatus::class,
    ];

    // ==========================================================================
    // Relationships
    // ==========================================================================

    /**
     * Get the child for this attendance.
     */
    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class);
    }

    /**
     * Get the childcare center for this attendance.
     */
    public function childcareCenter(): BelongsTo
    {
        return $this->belongsTo(ChildcareCenter::class);
    }

    /**
     * Get the user who registered this attendance.
     */
    public function registeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by');
    }

    // ==========================================================================
    // Helpers
    // ==========================================================================

    /**
     * Check if the child is present.
     */
    public function isPresent(): bool
    {
        return $this->status === AttendanceStatus::PRESENT
            || $this->status === AttendanceStatus::LATE;
    }

    /**
     * Check if the child has checked out.
     */
    public function hasCheckedOut(): bool
    {
        return $this->check_out_time !== null;
    }

    /**
     * Mark check-in.
     */
    public function checkIn(): void
    {
        $this->update([
            'check_in_time' => now()->format('H:i'),
            'status' => AttendanceStatus::PRESENT,
        ]);
    }

    /**
     * Mark check-out.
     */
    public function checkOut(): void
    {
        $this->update([
            'check_out_time' => now()->format('H:i'),
        ]);
    }
}

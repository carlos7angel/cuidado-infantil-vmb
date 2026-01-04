<?php

namespace App\Containers\Monitoring\ChildEnrollment\Models;

use App\Containers\AppSection\File\Models\File;
use App\Containers\Monitoring\Child\Models\Child;
use App\Containers\Monitoring\ChildcareCenter\Models\ChildcareCenter;
use App\Containers\Monitoring\ChildEnrollment\Enums\EnrollmentStatus;
use App\Containers\Monitoring\Room\Models\Room;
use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ChildEnrollment extends ParentModel
{
    /**
     * Boot the model and register event listeners.
     */
    protected static function booted(): void
    {
        // Al crear una nueva inscripción activa, desactivar las anteriores
        static::creating(function (ChildEnrollment $enrollment) {
            if ($enrollment->status === EnrollmentStatus::ACTIVE) {
                self::deactivateOtherEnrollments($enrollment->child_id);
            }
        });

        // Al actualizar a estado activo, desactivar las otras
        static::updating(function (ChildEnrollment $enrollment) {
            if ($enrollment->isDirty('status') && $enrollment->status === EnrollmentStatus::ACTIVE) {
                self::deactivateOtherEnrollments($enrollment->child_id, $enrollment->id);
            }
        });
    }

    /**
     * Deactivate other active enrollments for a child.
     */
    private static function deactivateOtherEnrollments(int $childId, ?int $excludeId = null): void
    {
        $query = self::where('child_id', $childId)
            ->where('status', EnrollmentStatus::ACTIVE->value);

        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId);
        }

        $query->update([
            'status' => EnrollmentStatus::INACTIVE->value,
            'withdrawal_date' => now(),
        ]);
    }

    /**
     * @var list<string>
     */
    protected $fillable = [
        'child_id',
        'childcare_center_id',
        'room_id',
        'enrollment_date',
        'withdrawal_date',
        'status',
        // Documentos
        'file_admission_request',
        'file_commitment',
        'file_birth_certificate',
        'file_vaccination_card',
        'file_parent_id',
        'file_work_certificate',
        'file_utility_bill',
        'file_home_sketch',
        'file_residence_certificate',
        'file_pickup_authorization',
        'observations',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'enrollment_date' => 'date',
        'withdrawal_date' => 'date',
        'status' => EnrollmentStatus::class,
    ];

    // ==========================================================================
    // Relationships
    // ==========================================================================

    /**
     * Get the child for this enrollment.
     */
    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class);
    }

    /**
     * Get the childcare center for this enrollment.
     */
    public function childcareCenter(): BelongsTo
    {
        return $this->belongsTo(ChildcareCenter::class);
    }

    /**
     * Get the room/group for this enrollment.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get the admission request file.
     */
    public function admissionRequestFile(): BelongsTo
    {
        return $this->belongsTo(File::class, 'file_admission_request');
    }

    /**
     * Get the commitment file.
     */
    public function commitmentFile(): BelongsTo
    {
        return $this->belongsTo(File::class, 'file_commitment');
    }

    /**
     * Get the birth certificate file.
     */
    public function birthCertificateFile(): BelongsTo
    {
        return $this->belongsTo(File::class, 'file_birth_certificate');
    }

    /**
     * Get the vaccination card file.
     */
    public function vaccinationCardFile(): BelongsTo
    {
        return $this->belongsTo(File::class, 'file_vaccination_card');
    }

    /**
     * Get the parent ID file.
     */
    public function parentIdFile(): BelongsTo
    {
        return $this->belongsTo(File::class, 'file_parent_id');
    }

    /**
     * Get the work certificate file.
     */
    public function workCertificateFile(): BelongsTo
    {
        return $this->belongsTo(File::class, 'file_work_certificate');
    }

    /**
     * Get the utility bill file.
     */
    public function utilityBillFile(): BelongsTo
    {
        return $this->belongsTo(File::class, 'file_utility_bill');
    }

    /**
     * Get the home sketch file.
     */
    public function homeSketchFile(): BelongsTo
    {
        return $this->belongsTo(File::class, 'file_home_sketch');
    }

    /**
     * Get the residence certificate file.
     */
    public function residenceCertificateFile(): BelongsTo
    {
        return $this->belongsTo(File::class, 'file_residence_certificate');
    }

    /**
     * Get the pickup authorization file.
     */
    public function pickupAuthorizationFile(): BelongsTo
    {
        return $this->belongsTo(File::class, 'file_pickup_authorization');
    }

    // ==========================================================================
    // Helpers
    // ==========================================================================

    /**
     * Check if enrollment is active.
     */
    public function isActive(): bool
    {
        return $this->status === EnrollmentStatus::ACTIVE;
    }

    /**
     * Check if all required documents are uploaded.
     */
    public function hasAllDocuments(): bool
    {
        return !empty($this->file_admission_request)
            && !empty($this->file_commitment)
            && !empty($this->file_birth_certificate)
            && !empty($this->file_vaccination_card)
            && !empty($this->file_parent_id);
    }
}

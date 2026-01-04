<?php

namespace App\Containers\Monitoring\IncidentReport\Models;

use App\Containers\AppSection\User\Models\User;
use App\Containers\Monitoring\Child\Models\Child;
use App\Containers\Monitoring\ChildcareCenter\Models\ChildcareCenter;
use App\Containers\Monitoring\IncidentReport\Enums\IncidentSeverity;
use App\Containers\Monitoring\IncidentReport\Enums\IncidentStatus;
use App\Containers\Monitoring\IncidentReport\Enums\IncidentType;
use App\Containers\Monitoring\Room\Models\Room;
use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

final class IncidentReport extends ParentModel
{
    /**
     * Boot the model and register event listeners.
     */
    protected static function booted(): void
    {
        // Generar código único automáticamente al crear
        static::creating(function (IncidentReport $report) {
            if (empty($report->code)) {
                $report->code = self::generateUniqueCode();
            }
        });

        // Establecer fecha de reporte si no existe
        static::creating(function (IncidentReport $report) {
            if (empty($report->reported_at)) {
                $report->reported_at = now();
            }
        });
    }

    /**
     * Generate a unique code for the incident report.
     * Format: INC-YYYY-XXXX (e.g., INC-2025-0001)
     */
    private static function generateUniqueCode(): string
    {
        $prefix = 'INC';
        $year = now()->format('Y');
        $baseCode = "{$prefix}-{$year}-";

        // Buscar el último código del año
        $lastReport = self::where('code', 'like', "{$baseCode}%")
            ->orderByDesc('code')
            ->first();

        if ($lastReport) {
            // Extraer el número secuencial
            $lastNumber = (int) Str::afterLast($lastReport->code, '-');
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return sprintf('%s%04d', $baseCode, $nextNumber);
    }

    /**
     * @var list<string>
     */
    protected $fillable = [
        'code',
        'status',
        'child_id',
        'type',
        'severity_level',
        'description',
        'incident_date',
        'incident_time',
        'incident_location',
        'people_involved',
        'witnesses',
        'reported_by',
        'reported_at',
        'has_evidence',
        'evidence_description',
        'evidence_file_ids',
        'actions_taken',
        'additional_comments',
        'closed_date',
        'childcare_center_id',
        'room_id',
        // Campos opcionales (no se usarán por ahora, pero se mantienen)
        'evaluated_by',
        'evaluation_date',
        'evaluation_result',
        'escalated_date',
        'escalated_to',
        'escalation_reason',
        'requires_authority_notification',
        'notified_to_authorities',
        'authority_notification_date',
        'authority_notification_details',
        'follow_up_required',
        'next_follow_up_date',
        'follow_up_notes',
        'preventive_measures',
        'related_incident_ids',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'incident_date' => 'date',
        'reported_at' => 'datetime',
        'has_evidence' => 'boolean',
        'evidence_file_ids' => 'array',
        'closed_date' => 'date',
        'evaluation_date' => 'date',
        'escalated_date' => 'date',
        'requires_authority_notification' => 'boolean',
        'notified_to_authorities' => 'boolean',
        'authority_notification_date' => 'date',
        'follow_up_required' => 'boolean',
        'next_follow_up_date' => 'date',
        'related_incident_ids' => 'array',
        'status' => IncidentStatus::class,
        'type' => IncidentType::class,
        'severity_level' => IncidentSeverity::class,
    ];

    // ==========================================================================
    // Relationships
    // ==========================================================================

    /**
     * Get the child involved in this incident.
     */
    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class);
    }

    /**
     * Get the user who reported this incident.
     */
    public function reportedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    /**
     * Get the childcare center where the incident occurred.
     */
    public function childcareCenter(): BelongsTo
    {
        return $this->belongsTo(ChildcareCenter::class);
    }

    /**
     * Get the room where the incident occurred.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get the user who evaluated this incident.
     */
    public function evaluatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluated_by');
    }

    /**
     * Get the files attached to this incident report.
     */
    public function files(): MorphMany
    {
        return $this->morphMany(\App\Containers\AppSection\File\Models\File::class, 'filleable');
    }

    /**
     * Get the evidence files for this incident report.
     * Returns files based on evidence_file_ids array.
     */
    public function evidenceFiles()
    {
        if (empty($this->evidence_file_ids) || !is_array($this->evidence_file_ids)) {
            return collect();
        }

        // Convertir IDs a enteros para la consulta
        $fileIds = array_map('intval', $this->evidence_file_ids);
        
        if (empty($fileIds)) {
            return collect();
        }

        return \App\Containers\AppSection\File\Models\File::whereIn('id', $fileIds)
            ->orderByRaw('FIELD(id, ' . implode(',', $fileIds) . ')')
            ->get();
    }

    // ==========================================================================
    // Accessors & Helpers
    // ==========================================================================

    /**
     * Check if the incident requires immediate attention.
     */
    public function requiresImmediateAttention(): bool
    {
        return $this->severity_level->requiresImmediateAttention()
            || $this->type->requiresImmediateAttention()
            || $this->status->requiresAttention();
    }

    /**
     * Check if the incident requires authority notification.
     */
    public function requiresAuthorityNotification(): bool
    {
        return $this->requires_authority_notification
            || $this->severity_level->requiresAuthorityNotification()
            || $this->type->requiresAuthorityNotification();
    }

    /**
     * Check if the incident is active (not closed or archived).
     */
    public function isActive(): bool
    {
        return $this->status->isActive();
    }

    /**
     * Get formatted incident date and time.
     */
    public function getFormattedIncidentDateTimeAttribute(): ?string
    {
        if (!$this->incident_date) {
            return null;
        }

        $date = $this->incident_date->format('d/m/Y');
        $time = $this->incident_time ? date('H:i', strtotime($this->incident_time)) : null;

        return $time ? "{$date} {$time}" : $date;
    }

    /**
     * Get formatted reported date and time.
     */
    public function getFormattedReportedAtAttribute(): ?string
    {
        return $this->reported_at?->format('d/m/Y H:i');
    }
}

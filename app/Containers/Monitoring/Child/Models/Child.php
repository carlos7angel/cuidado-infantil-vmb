<?php

namespace App\Containers\Monitoring\Child\Models;

use App\Containers\AppSection\User\Enums\Gender;
use App\Containers\Monitoring\ChildDevelopment\Models\ChildDevelopmentEvaluation;
use App\Containers\Monitoring\ChildEnrollment\Models\ChildEnrollment;
use App\Containers\Monitoring\ChildVaccination\Models\ChildVaccination;
use App\Containers\Monitoring\ChildVaccination\Models\VaccineDose;
use App\Containers\Monitoring\IncidentReport\Models\IncidentReport;
use App\Containers\Monitoring\NutritionalAssessment\Models\NutritionalAssessment;
use App\Ship\Parents\Models\Model as ParentModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

final class Child extends ParentModel
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'paternal_last_name',
        'maternal_last_name',
        'birth',
        'gender',
        'avatar',
        'birth_date',
        'language',
        'country',
        'state',
        'city',
        'municipality',
        'address',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'birth' => 'date',
        'birth_date' => 'date',
        'gender' => Gender::class,
    ];

    // ==========================================================================
    // Accessors
    // ==========================================================================

    /**
     * Get the child's full name.
     */
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn (): string => "{$this->first_name} {$this->paternal_last_name} {$this->maternal_last_name}",
        );
    }

    /**
     * Get the child's age.
     */
    protected function age(): Attribute
    {
        return Attribute::make(
            get: fn (): ?int => $this->birth_date?->age,
        );
    }

    /**
     * Get age as human-readable string (years and months).
     * Uses the static formatAgeFromMonths method to ensure consistency.
     */
    public function getAgeReadableAttribute(): string
    {
        $ageInMonths = $this->getAgeInMonthsAt(now());
        
        if ($ageInMonths === null) {
            return '-';
        }
        
        return self::formatAgeFromMonths($ageInMonths);
    }

    /**
     * Get initials (first letter of first_name and first letter of paternal_last_name).
     */
    public function getInitialsAttribute(): string
    {
        $initials = '';
        
        if ($this->first_name) {
            $initials .= strtoupper(substr($this->first_name, 0, 1));
        }
        
        if ($this->paternal_last_name) {
            $initials .= strtoupper(substr($this->paternal_last_name, 0, 1));
        }
        
        return $initials ?: '??';
    }

    // ==========================================================================
    // Relationships
    // ==========================================================================

    /**
     * Get the social record for this child (1:1).
     */
    public function socialRecord(): HasOne
    {
        return $this->hasOne(ChildSocialRecord::class);
    }

    /**
     * Get the medical record for this child (1:1).
     */
    public function medicalRecord(): HasOne
    {
        return $this->hasOne(ChildMedicalRecord::class);
    }

    /**
     * Get the enrollments for this child.
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(ChildEnrollment::class);
    }

    /**
     * Get the active enrollment for this child.
     */
    public function activeEnrollment(): HasOne
    {
        return $this->hasOne(ChildEnrollment::class)->where('status', 'activo');
    }

    /**
     * Get the vaccinations for this child.
     */
    public function vaccinations(): HasMany
    {
        return $this->hasMany(ChildVaccination::class);
    }

    /**
     * Get the nutritional assessments for this child.
     */
    public function nutritionalAssessments(): HasMany
    {
        return $this->hasMany(NutritionalAssessment::class)->orderByDesc('assessment_date');
    }

    /**
     * Get the latest nutritional assessment for this child.
     */
    public function latestNutritionalAssessment(): HasOne
    {
        return $this->hasOne(NutritionalAssessment::class)->latestOfMany('assessment_date');
    }

    /**
     * Get the development evaluations for this child.
     */
    public function developmentEvaluations(): HasMany
    {
        return $this->hasMany(ChildDevelopmentEvaluation::class)->orderByDesc('evaluation_date');
    }

    /**
     * Get the latest development evaluation for this child.
     */
    public function latestDevelopmentEvaluation(): HasOne
    {
        return $this->hasOne(ChildDevelopmentEvaluation::class)->latestOfMany('evaluation_date');
    }

    /**
     * Get the incident reports for this child.
     */
    public function incidentReports(): HasMany
    {
        return $this->hasMany(IncidentReport::class)->orderByDesc('incident_date');
    }

    /**
     * Get the files attached to this child.
     */
    public function files(): MorphMany
    {
        return $this->morphMany(\App\Containers\AppSection\File\Models\File::class, 'filleable');
    }

    // ==========================================================================
    // Vaccination Helpers
    // ==========================================================================

    /**
     * Get the child's age in months (calculated to now).
     */
    public function getAgeInMonthsAttribute(): ?int
    {
        return $this->getAgeInMonthsAt(now());
    }

    /**
     * Calculate the child's age in months at a specific date.
     * This is the single source of truth for age calculation in the application.
     *
     * @param \Carbon\Carbon|\DateTime|string|null $date The date to calculate age at. If null, uses now().
     * @param bool $roundUp If true, rounds up to the next month (default). If false, rounds down to the current month.
     * @return int|null Age in months, or null if birth_date is not set
     */
    public function getAgeInMonthsAt($date = null, bool $roundUp = true): ?int
    {
        if (!$this->birth_date) {
            return null;
        }

        $targetDate = $date ? \Carbon\Carbon::parse($date) : now();
        $months = $this->birth_date->diffInMonths($targetDate);
        
        return $roundUp ? (int) ceil($months) : (int) floor($months);
    }

    /**
     * Format age in months as human-readable string.
     * This is a static method that can be used anywhere in the application
     * to format age from months, ensuring consistency across the codebase.
     *
     * @param int $months Age in months
     * @return string Formatted age string (e.g., "2 años y 3 meses", "5 meses", "1 año")
     */
    public static function formatAgeFromMonths(int $months): string
    {
        if ($months < 12) {
            return "{$months} " . ($months === 1 ? 'mes' : 'meses');
        }

        $years = intdiv($months, 12);
        $remainingMonths = $months % 12;

        if ($remainingMonths === 0) {
            return "{$years} " . ($years === 1 ? 'año' : 'años');
        }

        return "{$years} " . ($years === 1 ? 'año' : 'años') . " y {$remainingMonths} " . ($remainingMonths === 1 ? 'mes' : 'meses');
    }

    /**
     * Get applied vaccine dose IDs.
     */
    public function getAppliedVaccineDoseIds(): array
    {
        return $this->vaccinations()->pluck('vaccine_dose_id')->toArray();
    }

    /**
     * Get vaccine doses that should be applied NOW (child is within valid age range).
     * These are pending doses that the child CAN receive at their current age.
     */
    public function getDueVaccineDoses()
    {
        $ageInMonths = $this->age_in_months ?? 0;
        $appliedDoseIds = $this->getAppliedVaccineDoseIds();

        return VaccineDose::whereNotIn('id', $appliedDoseIds)
            ->whereHas('vaccine', fn ($q) => $q->where('is_required', true))
            ->where('min_age_months', '<=', $ageInMonths)
            ->where(function ($query) use ($ageInMonths) {
                $query->whereNull('max_age_months')
                      ->orWhere('max_age_months', '>=', $ageInMonths);
            })
            ->with('vaccine')
            ->orderBy('recommended_age_months')
            ->get();
    }

    /**
     * Get overdue vaccine doses (past recommended age but still valid).
     */
    public function getOverdueVaccineDoses()
    {
        $ageInMonths = $this->age_in_months ?? 0;
        $appliedDoseIds = $this->getAppliedVaccineDoseIds();

        return VaccineDose::whereNotIn('id', $appliedDoseIds)
            ->whereHas('vaccine', fn ($q) => $q->where('is_required', true))
            ->where('recommended_age_months', '<', $ageInMonths)
            ->where('min_age_months', '<=', $ageInMonths)
            ->where(function ($query) use ($ageInMonths) {
                $query->whereNull('max_age_months')
                      ->orWhere('max_age_months', '>=', $ageInMonths);
            })
            ->with('vaccine')
            ->orderBy('recommended_age_months')
            ->get();
    }

    /**
     * Get expired vaccine doses (child exceeded max age).
     */
    public function getExpiredVaccineDoses()
    {
        $ageInMonths = $this->age_in_months ?? 0;
        $appliedDoseIds = $this->getAppliedVaccineDoseIds();

        return VaccineDose::whereNotIn('id', $appliedDoseIds)
            ->whereHas('vaccine', fn ($q) => $q->where('is_required', true))
            ->whereNotNull('max_age_months')
            ->where('max_age_months', '<', $ageInMonths)
            ->with('vaccine')
            ->orderBy('recommended_age_months')
            ->get();
    }

    /**
     * Get upcoming vaccine doses (child hasn't reached min age yet).
     */
    public function getUpcomingVaccineDoses(int $monthsAhead = 6)
    {
        $ageInMonths = $this->age_in_months ?? 0;
        $appliedDoseIds = $this->getAppliedVaccineDoseIds();

        return VaccineDose::whereNotIn('id', $appliedDoseIds)
            ->where('min_age_months', '>', $ageInMonths)
            ->where('min_age_months', '<=', $ageInMonths + $monthsAhead)
            ->with('vaccine')
            ->orderBy('min_age_months')
            ->get();
    }

    /**
     * Get complete vaccination status with all doses grouped by status.
     *
     * @return array{due: Collection, overdue: Collection, upcoming: Collection, expired: Collection, applied: Collection}
     */
    public function getVaccinationStatus(): array
    {
        return [
            'due' => $this->getDueVaccineDoses(),           // Toca ahora (en edad válida)
            'overdue' => $this->getOverdueVaccineDoses(),   // Atrasadas pero aún válidas
            'upcoming' => $this->getUpcomingVaccineDoses(), // Próximas (aún no tiene edad)
            'expired' => $this->getExpiredVaccineDoses(),   // Ya no puede recibirlas
            'applied' => $this->vaccinations()->with('vaccineDose.vaccine')->get(),
        ];
    }

    /**
     * Check if child has complete vaccination for their age (no overdue required doses).
     */
    public function hasCompleteVaccinationForAge(): bool
    {
        return $this->getOverdueVaccineDoses()->count() === 0;
    }

    /**
     * Check if child has any pending required doses they can receive now.
     */
    public function hasPendingVaccinations(): bool
    {
        return $this->getDueVaccineDoses()->count() > 0;
    }

    // ==========================================================================
    // Nutritional Assessment Helpers
    // ==========================================================================

    /**
     * Get the growth trend (comparing last two assessments).
     */
    public function getNutritionalTrend(): ?array
    {
        $assessments = $this->nutritionalAssessments()->limit(2)->get();

        if ($assessments->count() < 2) {
            return null;
        }

        $latest = $assessments->first();
        $previous = $assessments->last();

        return [
            'weight_change' => round($latest->weight - $previous->weight, 2),
            'height_change' => round($latest->height - $previous->height, 2),
            'days_between' => $previous->assessment_date->diffInDays($latest->assessment_date),
            'improving' => $latest->z_weight_height > $previous->z_weight_height,
        ];
    }

    /**
     * Check if child needs nutritional follow-up.
     */
    public function needsNutritionalFollowUp(): bool
    {
        $latest = $this->latestNutritionalAssessment;

        if (!$latest) {
            return true; // No tiene valoraciones
        }

        // Si requiere atención por su estado nutricional
        if ($latest->requiresAttention()) {
            return true;
        }

        // Si ya pasó la fecha de próxima valoración
        if ($latest->next_assessment_date && $latest->next_assessment_date->isPast()) {
            return true;
        }

        return false;
    }
}

<?php

namespace App\Containers\Monitoring\ChildVaccination\UI\API\Transformers;

use App\Containers\Monitoring\Child\Models\Child;
use App\Containers\Monitoring\ChildVaccination\Models\ChildVaccination;
use App\Containers\Monitoring\ChildVaccination\Models\Vaccine;
use App\Containers\Monitoring\ChildVaccination\Models\VaccineDose;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;
use Illuminate\Support\Collection;

final class ChildVaccinationTrackingTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    /**
     * Transform vaccination tracking data.
     *
     * @param array{child: Child, vaccines: Collection, applied_vaccinations: Collection} $data
     */
    public function transform(array $data): array
    {
        $child = $data['child'];
        $vaccines = $data['vaccines'];
        $appliedVaccinations = $data['applied_vaccinations'];

        $ageInMonths = $child->age_in_months ?? 0;

        // Calculate summary statistics
        $summary = $this->calculateSummary($vaccines, $appliedVaccinations, $ageInMonths);

        // Build vaccines array with doses
        $vaccinesArray = $vaccines->map(function (Vaccine $vaccine) use ($appliedVaccinations, $ageInMonths) {
            return $this->transformVaccine($vaccine, $appliedVaccinations, $ageInMonths);
        })->values();

        // Build timeline (only applied vaccinations, sorted by date)
        $timeline = $this->buildTimeline($appliedVaccinations);

        return [
            'child' => [
                'id' => $child->getHashedKey(),
                'name' => $child->full_name,
                'age_in_months' => $ageInMonths,
                'age_readable' => $this->formatAgeReadable($ageInMonths),
                'birth_date' => $child->birth_date?->format('Y-m-d'),
            ],
            'summary' => $summary,
            'vaccines' => $vaccinesArray,
            'timeline' => $timeline,
        ];
    }

    /**
     * Calculate summary statistics.
     */
    private function calculateSummary(Collection $vaccines, Collection $appliedVaccinations, int $ageInMonths): array
    {
        $totalDoses = 0;
        $appliedCount = 0;
        $pendingCount = 0;
        $overdueCount = 0;
        $upcomingCount = 0;
        $expiredCount = 0;

        foreach ($vaccines as $vaccine) {
            foreach ($vaccine->doses as $dose) {
                $totalDoses++;
                $isApplied = $appliedVaccinations->has($dose->id);

                if ($isApplied) {
                    $appliedCount++;
                } else {
                    $status = $dose->getStatusForAge($ageInMonths);
                    switch ($status) {
                        case 'overdue':
                            $overdueCount++;
                            $pendingCount++;
                            break;
                        case 'expired':
                            $expiredCount++;
                            $pendingCount++;
                            break;
                        case 'too_young':
                            $upcomingCount++;
                            break;
                        default:
                            $pendingCount++;
                    }
                }
            }
        }

        $completionPercentage = $totalDoses > 0
            ? round(($appliedCount / $totalDoses) * 100, 2)
            : 0;

        return [
            'total_vaccines' => $vaccines->count(),
            'total_doses' => $totalDoses,
            'applied_count' => $appliedCount,
            'pending_count' => $pendingCount,
            'overdue_count' => $overdueCount,
            'upcoming_count' => $upcomingCount,
            'expired_count' => $expiredCount,
            'completion_percentage' => $completionPercentage,
        ];
    }

    /**
     * Transform a vaccine with its doses.
     */
    private function transformVaccine(Vaccine $vaccine, Collection $appliedVaccinations, int $ageInMonths): array
    {
        $dosesArray = $vaccine->doses->map(function (VaccineDose $dose) use ($appliedVaccinations, $ageInMonths) {
            return $this->transformDose($dose, $appliedVaccinations, $ageInMonths);
        })->values();

        // Calculate progress for this vaccine
        $applied = $dosesArray->where('status', 'applied')->count();
        $total = $dosesArray->count();
        $percentage = $total > 0 ? round(($applied / $total) * 100, 2) : 0;

        return [
            'vaccine' => [
                'id' => $vaccine->getHashedKey(),
                'name' => $vaccine->name,
                'description' => $vaccine->description,
                'total_doses' => $vaccine->total_doses,
                'is_required' => $vaccine->is_required,
            ],
            'doses' => $dosesArray,
            'progress' => [
                'applied' => $applied,
                'total' => $total,
                'percentage' => $percentage,
                'is_complete' => $applied === $total,
            ],
        ];
    }

    /**
     * Transform a vaccine dose with its status and vaccination info.
     */
    private function transformDose(VaccineDose $dose, Collection $appliedVaccinations, int $ageInMonths): array
    {
        $isApplied = $appliedVaccinations->has($dose->id);
        $childVaccination = $isApplied ? $appliedVaccinations->get($dose->id) : null;
        $ageStatus = $dose->getStatusForAge($ageInMonths);

        // Determine status
        if ($isApplied) {
            $status = 'applied';
            $statusLabel = 'Aplicada';
            $statusColor = 'success';
        } else {
            switch ($ageStatus) {
                case 'overdue':
                    $status = 'overdue';
                    $statusLabel = 'Atrasada';
                    $statusColor = 'warning';
                    break;
                case 'expired':
                    $status = 'expired';
                    $statusLabel = 'Expirada';
                    $statusColor = 'danger';
                    break;
                case 'too_young':
                    $status = 'upcoming';
                    $statusLabel = 'Próxima';
                    $statusColor = 'info';
                    break;
                case 'ideal':
                    $status = 'due';
                    $statusLabel = 'Pendiente';
                    $statusColor = 'primary';
                    break;
                default:
                    $status = 'pending';
                    $statusLabel = 'Pendiente';
                    $statusColor = 'secondary';
            }
        }

        $result = [
            'dose' => [
                'id' => $dose->getHashedKey(),
                'dose_number' => $dose->dose_number,
                'recommended_age_months' => $dose->recommended_age_months,
                'min_age_months' => $dose->min_age_months,
                'max_age_months' => $dose->max_age_months,
                'recommended_age_readable' => $dose->recommended_age,
                'age_range_readable' => $dose->age_range,
                'description' => $dose->description,
            ],
            'status' => $status,
            'status_label' => $statusLabel,
            'status_color' => $statusColor,
            'age_status' => $ageStatus,
            'age_status_label' => $this->getAgeStatusLabel($ageStatus),
        ];

        // Add child vaccination data if applied
        if ($childVaccination) {
            $daysSinceApplied = now()->diffInDays($childVaccination->date_applied);
            $result['child_vaccination'] = [
                'id' => $childVaccination->getHashedKey(),
                'date_applied' => $childVaccination->date_applied->format('Y-m-d'),
                'registered_at' => $childVaccination->created_at->format('Y-m-d H:i:s'),
                'registered_at_readable' => $childVaccination->created_at->diffForHumans(),
                'applied_at' => $childVaccination->applied_at,
                'notes' => $childVaccination->notes,
                'days_since_applied' => $daysSinceApplied,
            ];
        } else {
            $result['child_vaccination'] = null;

            // Add additional info for pending doses
            if ($status === 'overdue') {
                $daysOverdue = $ageInMonths > $dose->recommended_age_months
                    ? ($ageInMonths - $dose->recommended_age_months) * 30 // Approximate
                    : 0;
                $result['days_overdue'] = $daysOverdue;
            } elseif ($status === 'upcoming') {
                $monthsUntilAvailable = max(0, $dose->min_age_months - $ageInMonths);
                $result['months_until_available'] = $monthsUntilAvailable;
            }
        }

        return $result;
    }

    /**
     * Build timeline from applied vaccinations.
     */
    private function buildTimeline(Collection $appliedVaccinations): array
    {
        return $appliedVaccinations
            ->sortBy('date_applied')
            ->map(function (ChildVaccination $vaccination) {
                return [
                    'date' => $vaccination->date_applied->format('Y-m-d'),
                    'registered_at' => $vaccination->created_at->format('Y-m-d H:i:s'),
                    'registered_at_readable' => $vaccination->created_at->diffForHumans(),
                    'vaccine_name' => $vaccination->vaccineDose->vaccine->name,
                    'dose_number' => $vaccination->vaccineDose->dose_number,
                    'status' => 'applied',
                    'applied_at' => $vaccination->applied_at,
                    'vaccine_dose_id' => $vaccination->vaccineDose->getHashedKey(),
                ];
            })
            ->values()
            ->toArray();
    }

    /**
     * Get human-readable age status label.
     */
    private function getAgeStatusLabel(string $ageStatus): string
    {
        return match ($ageStatus) {
            'too_young' => 'Aún no tiene edad',
            'ideal' => 'Edad ideal',
            'overdue' => 'Atrasada pero aún puede recibirla',
            'expired' => 'Ya pasó la edad máxima',
            default => 'Pendiente',
        };
    }

    /**
     * Format age in months to human-readable string.
     */
    private function formatAgeReadable(int $months): string
    {
        if ($months === 0) {
            return 'al nacer';
        }

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
}


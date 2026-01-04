<?php

namespace App\Containers\Monitoring\ChildVaccination\Tasks;

use App\Containers\Monitoring\Child\Data\Repositories\ChildRepository;
use App\Containers\Monitoring\Child\Models\Child;
use App\Containers\Monitoring\ChildVaccination\Models\Vaccine;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Support\Collection;

final class GetChildVaccinationTrackingTask extends ParentTask
{
    public function __construct(
        private readonly ChildRepository $childRepository,
    ) {
    }

    /**
     * Get complete vaccination tracking for a child with optimized queries.
     *
     * @return array{child: Child, vaccines: Collection, applied_vaccinations: Collection}
     */
    public function run(int $childId): array
    {
        // Load child with minimal data
        $child = $this->childRepository->findOrFail($childId);

        // Get all required vaccines with their doses in one query
        $vaccines = Vaccine::where('is_required', true)
            ->with(['doses' => function ($query) {
                $query->orderBy('dose_number');
            }])
            ->orderBy('name')
            ->get();

        // Get all applied vaccinations for this child in one query
        $appliedVaccinations = $child->vaccinations()
            ->with(['vaccineDose.vaccine'])
            ->get()
            ->keyBy('vaccine_dose_id'); // Index by vaccine_dose_id for fast lookup

        return [
            'child' => $child,
            'vaccines' => $vaccines,
            'applied_vaccinations' => $appliedVaccinations,
        ];
    }
}


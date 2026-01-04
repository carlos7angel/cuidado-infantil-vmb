<?php

namespace App\Containers\Monitoring\ChildVaccination\Tasks;

use App\Containers\Monitoring\ChildVaccination\Data\Repositories\ChildVaccinationRepository;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class DeleteChildVaccinationTask extends ParentTask
{
    public function __construct(
        private readonly ChildVaccinationRepository $repository,
    ) {
    }

    /**
     * Delete a child vaccination record by ID.
     * The repository will throw an exception if the record doesn't exist.
     *
     * @param int|string $id The vaccination record ID (can be hashed)
     * @return bool True if deletion was successful
     */
    public function run($id): bool
    {
        // Find the record first to ensure it exists (will throw ModelNotFoundException if not found)
        $this->repository->findOrFail($id);

        // Delete the record
        return $this->repository->delete($id);
    }
}

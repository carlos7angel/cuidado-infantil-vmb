<?php

namespace App\Containers\Monitoring\Attendance\Data\Factories;

use App\Containers\Monitoring\Attendance\Models\Attendance;
use App\Ship\Parents\Factories\Factory as ParentFactory;

/**
 * @template TModel of Attendance
 *
 * @extends ParentFactory<TModel>
 */
final class AttendanceFactory extends ParentFactory
{
    /** @var class-string<TModel> */
    protected $model = Attendance::class;

    public function definition(): array
    {
        return [];
    }
}

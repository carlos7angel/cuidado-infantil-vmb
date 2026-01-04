<?php

namespace App\Containers\Monitoring\Room\Data\Repositories;

use App\Containers\Monitoring\Room\Models\Room;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of Room
 *
 * @extends ParentRepository<TModel>
 */
final class RoomRepository extends ParentRepository
{
    protected $fieldSearchable = [
        // 'id' => '=',
    ];
}

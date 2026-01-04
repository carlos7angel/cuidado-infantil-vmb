<?php

namespace App\Containers\Monitoring\ChildDevelopment\Data\Repositories;

use App\Containers\Monitoring\ChildDevelopment\Models\DevelopmentItem;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of DevelopmentItem
 *
 * @extends ParentRepository<TModel>
 */
final class DevelopmentItemRepository extends ParentRepository
{
    protected $fieldSearchable = [
        'id' => '=',
        'item_number' => '=',
        'area' => '=',
        'age_min_months' => '=',
        'age_max_months' => '=',
    ];

    public function model(): string
    {
        return DevelopmentItem::class;
    }
}


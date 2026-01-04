<?php

namespace App\Containers\Monitoring\Child\Data\Repositories;

use App\Containers\Monitoring\Child\Models\ChildSocialRecord;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

class ChildSocialRecordRepository extends ParentRepository
{
    protected $fieldSearchable = [
        'id' => '=',
        'child_id' => '=',
        'guardian_type' => '=',
        'housing_type' => '=',
        'transport_type' => '=',
    ];
}
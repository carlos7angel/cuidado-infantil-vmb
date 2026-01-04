<?php

namespace App\Containers\Monitoring\Child\Data\Repositories;

use App\Containers\Monitoring\Child\Models\ChildFamilyMember;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

class ChildFamilyMemberRepository extends ParentRepository
{
    protected $fieldSearchable = [
        'id' => '=',
        'child_social_record_id' => '=',
        'first_name' => 'like',
        'last_name' => 'like',
        'kinship' => '=',
        'has_income' => '=',
    ];
}
<?php

namespace App\Containers\Monitoring\Child\Data\Repositories;

use App\Containers\Monitoring\Child\Models\ChildMedicalRecord;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

class ChildMedicalRecordRepository extends ParentRepository
{
    protected $fieldSearchable = [
        'id' => '=',
        'child_id' => '=',
        'has_insurance' => '=',
        'has_allergies' => '=',
        'has_medical_treatment' => '=',
        'has_psychological_treatment' => '=',
        'has_deficit' => '=',
        'has_illness' => '=',
    ];
}
<?php

namespace App\Containers\Monitoring\ChildVaccination\UI\WEB\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class ListChildVaccinationsRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [];
    }
}

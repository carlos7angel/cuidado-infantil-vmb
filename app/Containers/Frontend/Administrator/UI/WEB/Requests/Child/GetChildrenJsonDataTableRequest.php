<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\Child;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class GetChildrenJsonDataTableRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'childcare_center_id' => 'nullable|exists:childcare_centers,id',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}


<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\Child;

use App\Ship\Parents\Requests\Request;

class ImportChildrenRequest extends Request
{
    public function rules(): array
    {
        return [
            // 'data' => 'required|array',
            // 'data.*.nombres' => 'required|string',
            // Add other validations as needed, but maybe kept loose here as we filter in Action
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

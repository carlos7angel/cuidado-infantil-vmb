<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\Child;

use App\Ship\Parents\Requests\Request;

class PreviewChildrenImportRequest extends Request
{
    public function rules(): array
    {
        return [
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB max
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

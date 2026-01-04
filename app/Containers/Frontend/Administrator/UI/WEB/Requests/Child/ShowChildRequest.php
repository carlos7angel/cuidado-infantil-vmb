<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\Child;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class ShowChildRequest extends ParentRequest
{
    protected array $decode = [];

    protected array $urlParameters = [
        'id',
    ];

    public function rules(): array
    {
        return [
            // 'id' => 'required|exists:children,id',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}


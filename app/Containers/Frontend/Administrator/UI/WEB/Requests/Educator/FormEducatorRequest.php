<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\Educator;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class FormEducatorRequest extends ParentRequest
{
    protected array $decode = [];

    protected array $urlParameters = [
        'id',
    ];

    public function rules(): array
    {
        return [
            'id' => 'sometimes|exists:educators,id',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}


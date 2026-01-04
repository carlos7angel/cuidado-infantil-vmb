<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\ChildcareCenter;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class FormChildcareCenterRequest extends ParentRequest
{
    protected array $decode = [];

    protected array $urlParameters = [
        'id',
    ];

    public function rules(): array
    {
        return [
            'id' => 'sometimes|exists:childcare_centers,id',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}


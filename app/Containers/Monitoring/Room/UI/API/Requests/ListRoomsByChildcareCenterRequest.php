<?php

namespace App\Containers\Monitoring\Room\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class ListRoomsByChildcareCenterRequest extends ParentRequest
{
    protected array $access = [
        'permissions' => null,
        'roles' => null,
    ];

    protected array $decode = [
        'childcare_center_id',
    ];

    protected array $urlParameters = [
        'childcare_center_id',
    ];

    public function rules(): array
    {
        return [
            // 'childcare_center_id' => 'required|exists:childcare_centers,id',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
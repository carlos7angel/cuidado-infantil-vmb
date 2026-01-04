<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\Room;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class FormRoomRequest extends ParentRequest
{
    protected array $decode = [];

    protected array $urlParameters = [
        'id',
    ];

    public function rules(): array
    {
        return [
            'id' => 'sometimes|exists:rooms,id',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}


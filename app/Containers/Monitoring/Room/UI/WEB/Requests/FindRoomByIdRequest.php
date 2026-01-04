<?php

namespace App\Containers\Monitoring\Room\UI\WEB\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class FindRoomByIdRequest extends ParentRequest
{
    protected array $decode = [
        'id',
    ];

    public function rules(): array
    {
        return [];
    }
}

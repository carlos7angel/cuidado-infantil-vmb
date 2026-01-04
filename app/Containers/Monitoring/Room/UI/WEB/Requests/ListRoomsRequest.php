<?php

namespace App\Containers\Monitoring\Room\UI\WEB\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class ListRoomsRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [];
    }
}

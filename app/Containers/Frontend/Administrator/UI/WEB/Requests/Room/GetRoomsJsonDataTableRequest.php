<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\Room;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class GetRoomsJsonDataTableRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [];
    }
}


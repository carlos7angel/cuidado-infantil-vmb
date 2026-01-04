<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class StoreAdministratorRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [];
    }
}

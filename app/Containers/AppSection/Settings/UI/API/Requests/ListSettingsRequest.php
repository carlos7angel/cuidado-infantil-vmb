<?php

namespace App\Containers\AppSection\Settings\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class ListSettingsRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [];
    }
}

<?php

namespace App\Containers\AppSection\Settings\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class UpdateSettingsRequest extends ParentRequest
{
    protected array $decode = [
        'id',
    ];

    public function rules(): array
    {
        return [];
    }
}

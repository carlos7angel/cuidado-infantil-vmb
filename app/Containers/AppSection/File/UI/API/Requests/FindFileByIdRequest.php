<?php

namespace App\Containers\AppSection\File\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class FindFileByIdRequest extends ParentRequest
{
    protected array $decode = [
        'id',
    ];

    public function rules(): array
    {
        return [];
    }
}

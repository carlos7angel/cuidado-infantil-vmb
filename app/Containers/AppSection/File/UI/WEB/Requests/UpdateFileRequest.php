<?php

namespace App\Containers\AppSection\File\UI\WEB\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class UpdateFileRequest extends ParentRequest
{
    protected array $decode = [
        'id',
    ];

    public function rules(): array
    {
        return [];
    }
}

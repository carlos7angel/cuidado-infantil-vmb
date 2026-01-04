<?php

namespace App\Containers\AppSection\File\UI\WEB\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class EditFileRequest extends ParentRequest
{
    protected array $decode = [
        'id',
    ];

    public function rules(): array
    {
        return [];
    }
}

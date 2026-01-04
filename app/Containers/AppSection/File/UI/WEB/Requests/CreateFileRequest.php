<?php

namespace App\Containers\AppSection\File\UI\WEB\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class CreateFileRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [];
    }
}

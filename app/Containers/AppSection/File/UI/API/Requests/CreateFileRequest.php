<?php

namespace App\Containers\AppSection\File\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class CreateFileRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [];
    }
}

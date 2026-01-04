<?php

namespace App\Containers\AppSection\Authentication\UI\API\Requests\AppClient;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class AppIssueTokenRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => 'required',
        ];
    }
}

<?php

namespace App\Containers\AppSection\Authentication\Actions\Api\AppClient;       

use App\Containers\AppSection\Authentication\Data\DTOs\PasswordToken;
use App\Containers\AppSection\Authentication\Data\Factories\PasswordTokenFactory;
use App\Containers\AppSection\Authentication\Values\Clients\AppClient;
use App\Containers\AppSection\Authentication\Values\RefreshToken;
use App\Containers\AppSection\Authentication\Values\RequestProxies\PasswordGrant\RefreshTokenProxy;
use App\Ship\Parents\Actions\Action as ParentAction;

final class AppRefreshTokenAction extends ParentAction
{
    public function __construct(
        private readonly PasswordTokenFactory $factory,
    ) {
    }

    public function run(RefreshToken $refreshToken): PasswordToken
    {
        return $this->factory->make(
            RefreshTokenProxy::create(
                $refreshToken,
                AppClient::create(),
            ),
        );
    }
}

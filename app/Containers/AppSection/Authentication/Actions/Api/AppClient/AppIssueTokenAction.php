<?php

namespace App\Containers\AppSection\Authentication\Actions\Api\AppClient;

use App\Containers\AppSection\Authentication\Data\DTOs\PasswordToken;
use App\Containers\AppSection\Authentication\Data\Factories\PasswordTokenFactory;
use App\Containers\AppSection\Authentication\Values\Clients\AppClient;
use App\Containers\AppSection\Authentication\Values\RequestProxies\PasswordGrant\AccessTokenProxy;
use App\Containers\AppSection\Authentication\Values\UserCredential;
use App\Containers\AppSection\Authorization\Enums\Role;
use App\Containers\AppSection\User\Tasks\FindUserByEmailTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class AppIssueTokenAction extends ParentAction
{
    public function __construct(
        private readonly PasswordTokenFactory $factory,
        private readonly FindUserByEmailTask $findUserByEmailTask,
    ) {
    }

    public function run(UserCredential $credential): PasswordToken
    {
        $user = $this->findUserByEmailTask->run($credential->username());

        if ($user) {
            if (!$user->active) {
                abort(403, 'Tu cuenta está inactiva. Comunícate con el administrador.');
            }

            if (!$user->hasRole(Role::EDUCATOR)) {
                abort(403, 'No tienes permisos para acceder a esta aplicación.');
            }
        }

        return $this->factory->make(
            AccessTokenProxy::create(
                $credential,
                AppClient::create(),
            ),
        );
    }
}

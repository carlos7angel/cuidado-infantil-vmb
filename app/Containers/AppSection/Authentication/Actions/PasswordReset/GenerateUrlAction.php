<?php

namespace App\Containers\AppSection\Authentication\Actions\PasswordReset;

use App\Containers\AppSection\Authentication\UI\API\Controllers\PasswordReset\ResetPasswordController;
use App\Containers\AppSection\User\Models\User;
use App\Ship\Apps\AppFactory;
use App\Ship\Parents\Actions\Action as ParentAction;

final class GenerateUrlAction extends ParentAction
{
    public function __invoke(User $notifiable, string $token): string
    {
        // Generate URL for the web reset password form, not the API
        $resetPasswordUrl = AppFactory::current()->resetPasswordUrl();

        // Add token and email as direct parameters
        $params = http_build_query([
            'token' => $token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        return $resetPasswordUrl . '?' . $params;
    }
}

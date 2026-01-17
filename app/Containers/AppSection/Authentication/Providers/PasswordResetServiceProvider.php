<?php

namespace App\Containers\AppSection\Authentication\Providers;

use App\Containers\AppSection\Authentication\Actions\PasswordReset\GenerateUrlAction;
use App\Ship\Parents\Providers\ServiceProvider as ParentServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

final class PasswordResetServiceProvider extends ParentServiceProvider
{
    public function boot(): void
    {
        // Usar URL personalizada para reset password
        ResetPassword::createUrlUsing(new GenerateUrlAction());

        // Usar contenido personalizado para el email de reset password
        ResetPassword::toMailUsing(function (object $notifiable, string $token) {
            $url = url(route('auth.reset_password', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            return (new MailMessage)
                ->subject('Restablecer Contraseña')
                ->view('appSection@authentication::passwordReset', [
                    'url' => $url,
                    'count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire', 60)
                ]);
        });
    }
}

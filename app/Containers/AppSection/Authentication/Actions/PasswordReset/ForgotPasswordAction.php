<?php

namespace App\Containers\AppSection\Authentication\Actions\PasswordReset;

use App\Containers\AppSection\Authentication\UI\API\Requests\PasswordReset\ForgotPasswordRequest;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

final class ForgotPasswordAction extends ParentAction
{
    public function run(ForgotPasswordRequest $request): string
    {
        $sanitizedData = $request->sanitize([
            'email',
        ]);

        $status = Password::sendResetLink($sanitizedData);

        return match ($status) {
            Password::RESET_LINK_SENT => 'Se ha enviado un enlace de recuperación de contraseña a su correo electrónico. Por favor, revise su bandeja de entrada.',
            Password::RESET_THROTTLED => throw ValidationException::withMessages([
                'throttle' => 'Demasiados intentos. Por favor, espere unos minutos antes de intentar nuevamente.'
            ]),
            default => throw ValidationException::withMessages([
                'email' => 'No se pudo enviar el enlace de recuperación. Verifique que el correo electrónico esté registrado en el sistema.'
            ]),
        };
    }
}

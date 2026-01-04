<?php

namespace App\Containers\AppSection\Authentication\Actions\Web;

use App\Containers\AppSection\ActivityLog\Constants\LogConstants;
use App\Containers\AppSection\ActivityLog\Events\ActivityLogEvent;
use App\Containers\AppSection\User\Models\User;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

final class LoginAction extends ParentAction
{
    public function run(string $email, string $password, bool $remember): RedirectResponse
    {
        $credentials = [
            'email' => static fn (Builder $query): Builder => $query
                ->orWhereRaw('lower(email) = lower(?)', [$email]),
            'password' => $password,
        ];

        $loggedIn = Auth::guard('web')->attempt($credentials, $remember);

        if ($loggedIn) {
            session()?->regenerate();

            /** @var User $user */
            $user = Auth::guard('web')->user();

            // Update last login timestamp
            $user->update(['last_login_at' => now()]);

            if (!$user->hasAdminWebRole()) {
                Auth::guard('web')->logout();
                session()?->invalidate();
                session()?->regenerateToken();

                return back()->with('status', 'No tienes permisos suficientes para acceder a esta sección.')->onlyInput('email');
            }

            ActivityLogEvent::dispatch(LogConstants::LOGIN_ADMIN, request()->server(), $user);

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors(['email' => 'Credenciales inválidas'])->onlyInput('email');
    }
}

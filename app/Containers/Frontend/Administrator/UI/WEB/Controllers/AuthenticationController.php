<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Controllers;

use App\Containers\AppSection\Authentication\Actions\PasswordReset\ForgotPasswordAction;
use App\Containers\AppSection\Authentication\Actions\PasswordReset\ResetPasswordAction;
use App\Containers\AppSection\Authentication\Actions\Web\LoginAction;
use App\Containers\AppSection\Authentication\Actions\Web\LogoutAction;
use App\Containers\AppSection\Authentication\UI\API\Requests\PasswordReset\ForgotPasswordRequest;
use App\Containers\AppSection\Authentication\UI\API\Requests\PasswordReset\ResetPasswordRequest;
use App\Containers\AppSection\Authentication\UI\WEB\Requests\LoginRequest;
use App\Containers\AppSection\Authentication\UI\WEB\Requests\LogoutRequest;
use App\Containers\AppSection\User\Actions\UpdatePasswordAction;
use App\Containers\AppSection\User\Actions\UpdateUserAction;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\UpdatePasswordProfileRequest;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\UpdateUsernameProfileRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

final class AuthenticationController extends WebController
{
    public function showLoginForm()
    {
        $page_title = 'Ingreso';
        if(Auth::guard('web')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('frontend@administrator::authentication.login', [], compact('page_title'));
    }

    public function postLogin(LoginRequest $request, LoginAction $action): RedirectResponse
    {
        return $action->run(
            $request->input('email'),
            $request->input('password'),
            $request->boolean('remember'),
        );
    }

    public function postLogout(LogoutRequest $request, LogoutAction $action): RedirectResponse
    {
        $action->run($request->session());

        // return redirect()->action(HomePageController::class);

        // app(WebLogoutAction::class)->run();
        // $request->session()->flush();
        return redirect()->route('auth.login');
    }

    public function formProfile()
    {
        $page_title = 'Perfil';
        $user = Auth::guard('web')->user();
        return view('frontend@administrator::authentication.profile', [], compact('page_title', 'user'));
    }

    public function updatePasswordProfile(UpdatePasswordProfileRequest $request, UpdatePasswordAction $updatePasswordAction): JsonResponse
    {
        try {
            $user = Auth::guard('web')->user();
            $updatePasswordAction->run($user->id, $request->input('password'));

            return response()->json([
                'success' => true,
                'message' => 'Contraseña actualizada exitosamente',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function updateUsernameProfile(UpdateUsernameProfileRequest $request, UpdateUserAction $updateUserAction): JsonResponse
    {
        try {
            $user = Auth::guard('web')->user();
            $updateUserAction->run($user->id, ['name' => $request->input('username')]);

            return response()->json([
                'success' => true,
                'message' => 'Nombre de usuario actualizado exitosamente',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function showForgotPasswordForm()
    {
        $page_title = 'Recuperar contraseña';
        if(Auth::guard('web')->check()) {
            return redirect()->route('admin.dashboard');
        }
        $url = url(route('auth.reset_password'));
        return view('frontend@administrator::authentication.forgotPassword', [], compact('page_title', 'url'));
    }

    public function postForgotPassword(ForgotPasswordRequest $request, ForgotPasswordAction $action): RedirectResponse
    {
        try {
            $result = $action->run($request);
            return redirect()->route('auth.forgot_password')
                ->with('success', $result);
        } catch (\Exception $e) {
            return redirect()->route('auth.forgot_password')
                ->with('error', $e->getMessage());
        }
    }

    public function showResetPasswordForm()
    {
        $page_title = "Restablecer contraseña";
        if (Auth::guard('web')->check()) {
            return redirect()->route('admin.dashboard');
        }
        $token = request()->get('token');
        $email = request()->get('email');
        return view('frontend@administrator::authentication.resetPassword', [], compact('page_title', 'token', 'email'));
    }

    public function postResetPassword(ResetPasswordRequest $request, ResetPasswordAction $action): RedirectResponse
    {
        try {
            $result = $action->run($request);
            return redirect()->route('auth.reset_password')
                ->with('success', $result);
        } catch (\Exception $e) {
            return redirect()->route('auth.reset_password')
                ->with('error', $e->getMessage());
        }
    }

    
}

<?php

use Apiato\Foundation\Apiato;
use Apiato\Foundation\Configuration\Routing;
use Apiato\Http\Middleware\ProcessETag;
use Apiato\Http\Middleware\ValidateJsonContent;
use App\Containers\AppSection\Authentication\UI\WEB\Controllers\HomePageController;
use App\Containers\AppSection\Authentication\UI\WEB\Controllers\LoginController;
use App\Containers\Frontend\Administrator\UI\WEB\Controllers\AuthenticationController;
use App\Containers\Frontend\Administrator\UI\WEB\Controllers\DashboardController;
use App\Ship\Middleware\ValidateAppId;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

$basePath = dirname(__DIR__);
// $apiato = Apiato::configure(basePath: $basePath)->create();
$apiato = Apiato::configure(basePath: $basePath)
    ->withRouting(function (Routing $routing) {
        // Set API prefix to root
        $routing->prefixApiUrlsWith('/');
        
        // Override version resolver to extract v1 from filename
        $routing->resolveApiVersionUsing(static function (string $file): string {
            // Extract version from route filename (e.g., AppIssueToken.v1.private.php -> v1)
            if (preg_match('/\.(v\d+)\.(private|public)\.php$/', $file, $matches)) {
                return $matches[1];
            }
            // Default to v1 if no version found
            return 'v1';
        });
    })
    ->create();

return Application::configure(basePath: $basePath)
    ->withProviders($apiato->providers())
    ->withEvents($apiato->events())
    ->withRouting(
        web: $apiato->webRoutes(),
        channels: __DIR__ . '/../app/Ship/Broadcasting/channels.php',
        health: '/up',
        then: static fn () => $apiato->registerApiRoutes(),
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->use([
            ValidateAppId::class,
        ]);
        $middleware->api(append: [
            ValidateJsonContent::class,
            ProcessETag::class,
        ]);
        $middleware->redirectUsersTo(static function (Request $request): string {
            // return action(HomePageController::class);
            return action(DashboardController::class, 'showIndexPage');
        });
        $middleware->redirectGuestsTo(static function (Request $request): string {
            // return action([LoginController::class, 'showForm']);
            return action([AuthenticationController::class, 'showLoginForm']);
        });
    })
    ->withCommands($apiato->commands())
    ->withExceptions(static function (Exceptions $exceptions) {})
    ->create();

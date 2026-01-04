<?php

namespace App\Containers\AppSection\Authentication\UI\WEB\Controllers;

use App\Containers\AppSection\Settings\Models\Settings;
use App\Ship\Parents\Controllers\WebController;
use App\Ship\Utils\HostHelper;
use Illuminate\Http\JsonResponse;

final class GetServerInfoController extends WebController
{
    public function __invoke(): JsonResponse
    {
        // Get settings from database
        $settings = Settings::pluck('value', 'key')->toArray();

        return response()->json([
            'host' => app(HostHelper::class)->getHost(),
            'api_version' => $settings['version_api'] ?? config('app.server.api_version'),
            'environment' => $settings['servidor_estado'] ?? config('app.env'),
            'server_status' => $settings['servidor_estado'] ?? config('app.server.status'),
            'municipality' => $settings['servidor_municipio'] ?? config('app.server.municipality'),
            'department' => $settings['servidor_departamento'] ?? config('app.server.department'),
        ]);
    }
}

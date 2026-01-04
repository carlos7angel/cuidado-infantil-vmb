<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Controllers;

use App\Containers\AppSection\Settings\Models\Settings;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\Settings\FormSettingsRequest;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\Settings\StoreSettingsRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

final class SettingsController extends WebController
{
    public function form(FormSettingsRequest $request): View
    {
        $page_title = "Ajustes de Configuración";

        // Obtener todos los settings actuales
        $settings = Settings::all()->pluck('value', 'key')->toArray();

        return view('frontend@administrator::settings.form', compact('page_title', 'settings'));
    }

    public function store(StoreSettingsRequest $request): JsonResponse
    {
        try {
            // Actualizar cada setting
            $settingsKeys = [
                'servidor_municipio',
                'servidor_departamento',
                'organizacion_nombre',
                'servidor_estado',
                'version_api',
                'sistema_nombre',
            ];

            foreach ($settingsKeys as $key) {
                if ($request->has($key)) {
                    Settings::set(
                        $key,
                        $request->input($key),
                        'string',
                        $this->getGroupByKey($key),
                        $this->getDescriptionByKey($key)
                    );
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Configuraciones actualizadas exitosamente',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    private function getGroupByKey(string $key): string
    {
        return match($key) {
            'servidor_municipio', 'servidor_departamento' => 'municipality',
            'organizacion_nombre' => 'organization',
            'servidor_estado', 'version_api' => 'server',
            'sistema_nombre' => 'system',
            default => 'general',
        };
    }

    private function getDescriptionByKey(string $key): string
    {
        return match($key) {
            'servidor_municipio' => 'Municipio',
            'servidor_departamento' => 'Departamento',
            'organizacion_nombre' => 'Nombre de la organización',
            'servidor_estado' => 'Estado del servidor (active, maintenance, etc.)',
            'version_api' => 'Versión de la API',
            'sistema_nombre' => 'Nombre del sistema',
            default => '',
        };
    }
}

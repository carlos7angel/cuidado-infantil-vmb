<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\Settings;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class StoreSettingsRequest extends ParentRequest
{
    protected array $decode = [];

    public function rules(): array
    {
        return [
            'servidor_municipio' => 'nullable|string|max:255',
            'servidor_departamento' => 'nullable|string|max:255',
            'organizacion_nombre' => 'nullable|string|max:255',
            'servidor_estado' => 'nullable|string|max:255',
            'version_api' => 'nullable|string|max:50',
            'sistema_nombre' => 'nullable|string|max:255',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}


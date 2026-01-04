<?php

namespace App\Containers\Monitoring\ChildDevelopment\UI\API\Requests;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class ListChildDevelopmentEvaluationsRequest extends ParentRequest
{
    protected array $decode = [
        'child_id',
    ];

    protected array $urlParameters = [
        'child_id',
    ];

    public function rules(): array
    {
        return [
            'child_id' => 'exists:children,id',
            // Los parámetros de paginación (limit, page) son manejados automáticamente
            // por addRequestCriteria() del repositorio, no necesitan validación aquí
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}


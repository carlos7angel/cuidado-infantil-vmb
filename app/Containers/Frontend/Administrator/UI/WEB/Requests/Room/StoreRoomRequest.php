<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\Room;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class StoreRoomRequest extends ParentRequest
{
    protected array $decode = [];

    protected array $urlParameters = [
        'id',
    ];

    public function rules(): array
    {
        $rules = [
            'childcare_center_id' => 'required|exists:childcare_centers,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'capacity' => 'required|integer|min:1',
            'is_active' => 'required|boolean',
        ];

        // Si es edición, hacer únicos condicionales
        $id = $this->route('id') ?? $this->input('id');
        if ($id) {
            // Podríamos agregar validaciones únicas aquí si es necesario
        }

        return $rules;
    }

    public function authorize(): bool
    {
        return true;
    }
}


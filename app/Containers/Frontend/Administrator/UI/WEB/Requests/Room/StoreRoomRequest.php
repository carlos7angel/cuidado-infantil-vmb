<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\Room;

use App\Containers\AppSection\Authorization\Enums\Role;
use App\Ship\Parents\Requests\Request as ParentRequest;

use App\Containers\Monitoring\Room\Models\Room;

final class StoreRoomRequest extends ParentRequest
{
    protected array $decode = [];

    protected array $urlParameters = [
        'id',
    ];

    protected function prepareForValidation()
    {
        if ($this->user()->hasRole(Role::CHILDCARE_ADMIN)) {
            $this->merge(['childcare_center_id' => $this->user()->childcare_center_id]);
        }
    }

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
        if (!$this->user()->hasAnyRole([Role::SUPER_ADMIN, Role::MUNICIPAL_ADMIN, Role::CHILDCARE_ADMIN])) {
            return false;
        }

        if ($this->user()->hasRole(Role::CHILDCARE_ADMIN)) {
            // Check if trying to assign to another center (though we overwrite it in prepareForValidation, 
            // explicit check if sent is good practice or just rely on overwrite)
            if ($this->has('childcare_center_id') && $this->input('childcare_center_id') != $this->user()->childcare_center_id) {
                // If the user manually sends a different ID, deny it? 
                // Or just overwrite it. 
                // Authorization-wise, if they try to act on another center, it's unauthorized.
                // But prepareForValidation runs before authorize? No, authorize runs first usually?
                // Actually authorize runs first.
                // So checking input here is valid.
                if ($this->input('childcare_center_id') != $this->user()->childcare_center_id) {
                     return false;
                }
            }

            // Check if updating an existing room
            $id = $this->route('id') ?? $this->input('id');
            if ($id) {
                $room = Room::find($id);
                if ($room && $room->childcare_center_id !== $this->user()->childcare_center_id) {
                    return false;
                }
            }
        }

        return true;
    }
}


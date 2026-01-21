<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\Room;

use App\Containers\AppSection\Authorization\Enums\Role;
use App\Ship\Parents\Requests\Request as ParentRequest;

use App\Containers\Monitoring\Room\Models\Room;

final class FormRoomRequest extends ParentRequest
{
    protected array $decode = [];

    protected array $urlParameters = [
        'id',
    ];

    public function rules(): array
    {
        return [
            'id' => 'sometimes|exists:rooms,id',
        ];
    }

    public function authorize(): bool
    {
        if (!$this->user()->hasAnyRole([Role::SUPER_ADMIN, Role::MUNICIPAL_ADMIN, Role::CHILDCARE_ADMIN])) {
            return false;
        }

        if ($this->user()->hasRole(Role::CHILDCARE_ADMIN) && $this->id) {
            $room = Room::find($this->id);
            if ($room && $room->childcare_center_id !== $this->user()->childcare_center_id) {
                return false;
            }
        }

        return true;
    }
}


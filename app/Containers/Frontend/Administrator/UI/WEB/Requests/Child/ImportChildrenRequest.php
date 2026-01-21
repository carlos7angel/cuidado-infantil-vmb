<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\Child;

use App\Containers\AppSection\Authorization\Enums\Role;
use App\Ship\Parents\Requests\Request;

class ImportChildrenRequest extends Request
{
    public function rules(): array
    {
        return [
            'childcare_center_id' => 'required|exists:childcare_centers,id',
            'file_id' => 'required|string', // Or however the file is passed
        ];
    }

    public function authorize(): bool
    {
        $user = $this->user();
        
        if (!$user->hasAnyRole([Role::SUPER_ADMIN, Role::MUNICIPAL_ADMIN, Role::CHILDCARE_ADMIN])) {
            return false;
        }

        if ($user->hasRole(Role::CHILDCARE_ADMIN)) {
            if ($this->input('childcare_center_id') != $user->childcare_center_id) {
                return false;
            }
        }

        return true;
    }
    
    protected function prepareForValidation()
    {
        $user = $this->user();
        if ($user->hasRole(Role::CHILDCARE_ADMIN)) {
            $this->merge([
                'childcare_center_id' => $user->childcare_center_id,
            ]);
        }
    }
}

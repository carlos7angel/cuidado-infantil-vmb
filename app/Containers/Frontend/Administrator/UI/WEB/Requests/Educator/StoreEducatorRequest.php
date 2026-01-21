<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\Educator;

use App\Containers\AppSection\Authorization\Enums\Role;
use App\Containers\AppSection\User\Enums\Gender;
use App\Containers\Monitoring\Educator\Models\Educator;
use App\Ship\Parents\Requests\Request as ParentRequest;
use Illuminate\Validation\Rule;

final class StoreEducatorRequest extends ParentRequest
{
    protected array $decode = [];

    protected array $urlParameters = [
        'id',
    ];

    protected function prepareForValidation()
    {
        if ($this->user()->hasRole(Role::CHILDCARE_ADMIN)) {
            $this->merge(['childcare_center_ids' => [$this->user()->childcare_center_id]]);
        }
    }

    public function rules(): array
    {
        $rules = [
            'email' => 'required|email|unique:users,email',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => ['required', Rule::enum(Gender::class)],
            'birth' => 'required|date_format:d/m/Y',
            'state' => 'nullable|string|max:255',
            'dni' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'contract_start_date' => 'nullable|date_format:d/m/Y',
            'contract_end_date' => 'nullable|date_format:d/m/Y',
            'childcare_center_ids' => 'required|array|min:1',
            'childcare_center_ids.*' => 'required|exists:childcare_centers,id',
        ];

        // Si es edición, el email puede ser el mismo (pero solo para este educator)
        $id = $this->route('id') ?? $this->input('id');
        if ($id) {
            // En edición no actualizamos el email (está vinculado al usuario)
            // Removemos la regla de email ya que no se actualiza
            unset($rules['email']);
        }

        return $rules;
    }

    public function authorize(): bool
    {
        if (!$this->user()->hasAnyRole([Role::SUPER_ADMIN, Role::MUNICIPAL_ADMIN, Role::CHILDCARE_ADMIN])) {
            return false;
        }

        if ($this->user()->hasRole(Role::CHILDCARE_ADMIN)) {
             $id = $this->route('id') ?? $this->input('id');
             if ($id) {
                 $educator = Educator::with('childcareCenters')->find($id);
                 if ($educator && !$educator->childcareCenters->contains('id', $this->user()->childcare_center_id)) {
                     return false;
                 }
             }
        }

        return true;
    }
}

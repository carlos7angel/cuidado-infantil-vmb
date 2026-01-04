<?php

namespace App\Containers\Frontend\Administrator\UI\WEB\Requests\ChildcareCenter;

use App\Ship\Parents\Requests\Request as ParentRequest;

final class StoreChildcareCenterRequest extends ParentRequest
{
    protected array $decode = [];

    protected array $urlParameters = [
        'id',
    ];

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255|unique:childcare_centers,name',
            'description' => 'nullable|string',
            'type' => 'nullable|string|max:255',
            'date_founded' => 'nullable|date_format:d/m/Y',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'social_media' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'state' => 'required|string|in:La Paz,Cochabamba,Santa Cruz,Potosí,Oruro,Beni,Pando,Chuquisaca,Tarija',
            'city' => 'nullable|string|max:255',
            'municipality' => 'required|string|max:255|unique:childcare_centers,municipality',
            'contact_name' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
        ];

        // Si es edición, hacer únicos condicionales
        $id = $this->route('id') ?? $this->input('id');
        if ($id) {
            $rules['name'] = 'required|string|max:255|unique:childcare_centers,name,' . $id;
            $rules['municipality'] = 'required|string|max:255|unique:childcare_centers,municipality,' . $id;
        }

        return $rules;
    }

    public function authorize(): bool
    {
        return true;
    }
}


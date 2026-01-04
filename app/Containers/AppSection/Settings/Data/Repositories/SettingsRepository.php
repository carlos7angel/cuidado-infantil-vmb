<?php

namespace App\Containers\AppSection\Settings\Data\Repositories;

use App\Containers\AppSection\Settings\Models\Settings;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of Settings
 *
 * @extends ParentRepository<TModel>
 */
final class SettingsRepository extends ParentRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id' => '=',
        'key' => '=',
        'type' => '=',
        'group' => '=',
    ];

    public function model(): string
    {
        return Settings::class;
    }

    /**
     * Obtener el valor de una configuración por su key
     */
    public function getValue(string $key, ?string $default = null): ?string
    {
        $setting = $this->findByField('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Establecer el valor de una configuración
     */
    public function setValue(string $key, ?string $value, ?string $type = null, ?string $group = null, ?string $description = null): Settings
    {
        return $this->updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group,
                'description' => $description,
            ]
        );
    }

    /**
     * Verificar si existe una configuración
     */
    public function has(string $key): bool
    {
        return $this->findByField('key', $key)->exists();
    }

    /**
     * Obtener todas las configuraciones como array asociativo
     */
    public function allAsArray(): array
    {
        return $this->all()->pluck('value', 'key')->toArray();
    }

    /**
     * Obtener todas las configuraciones de un grupo específico
     */
    public function getByGroup(string $group): array
    {
        return $this->findByField('group', $group)
            ->pluck('value', 'key')
            ->toArray();
    }
}

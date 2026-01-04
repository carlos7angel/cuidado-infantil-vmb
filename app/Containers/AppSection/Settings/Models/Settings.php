<?php

namespace App\Containers\AppSection\Settings\Models;

use App\Ship\Parents\Models\Model as ParentModel;

final class Settings extends ParentModel
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        //
    ];

    /**
     * Obtener el valor de una configuración por su key
     */
    public static function get(string $key, ?string $default = null): ?string
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Establecer el valor de una configuración
     */
    public static function set(string $key, ?string $value, ?string $type = null, ?string $group = null, ?string $description = null): void
    {
        static::updateOrCreate(
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
    public static function has(string $key): bool
    {
        return static::where('key', $key)->exists();
    }

    /**
     * Obtener todas las configuraciones de un grupo específico
     */
    public static function getByGroup(string $group): array
    {
        return static::where('group', $group)
            ->get()
            ->pluck('value', 'key')
            ->toArray();
    }
}

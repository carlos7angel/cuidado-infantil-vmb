<?php

namespace App\Containers\AppSection\Settings\Data\Factories;

use App\Containers\AppSection\Settings\Models\Settings;
use App\Ship\Parents\Factories\Factory as ParentFactory;

/**
 * @template TModel of Settings
 *
 * @extends ParentFactory<TModel>
 */
final class SettingsFactory extends ParentFactory
{
    /** @var class-string<TModel> */
    protected $model = Settings::class;

    public function definition(): array
    {
        return [];
    }
}

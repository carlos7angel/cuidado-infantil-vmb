<?php

namespace App\Containers\AppSection\Settings\UI\API\Transformers;

use App\Containers\AppSection\Settings\Models\Settings;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

final class SettingsTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(Settings $settings): array
    {
        return [
            'type' => $settings->getResourceKey(),
            'id' => $settings->getHashedKey(),
            'created_at' => $settings->created_at,
            'updated_at' => $settings->updated_at,
            'readable_created_at' => $settings->created_at->diffForHumans(),
            'readable_updated_at' => $settings->updated_at->diffForHumans(),
        ];
    }
}

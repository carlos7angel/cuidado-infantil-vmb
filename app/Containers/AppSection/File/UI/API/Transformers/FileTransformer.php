<?php

namespace App\Containers\AppSection\File\UI\API\Transformers;

use App\Containers\AppSection\File\Models\File;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

final class FileTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(File $file): array
    {
        return [
            'type' => $file->getResourceKey(),
            'id' => $file->getHashedKey(),
            'created_at' => $file->created_at,
            'updated_at' => $file->updated_at,
            'readable_created_at' => $file->created_at->diffForHumans(),
            'readable_updated_at' => $file->updated_at->diffForHumans(),
        ];
    }
}

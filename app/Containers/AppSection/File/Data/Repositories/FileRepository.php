<?php

namespace App\Containers\AppSection\File\Data\Repositories;

use App\Containers\AppSection\File\Models\File;
use App\Ship\Parents\Repositories\Repository as ParentRepository;

/**
 * @template TModel of File
 *
 * @extends ParentRepository<TModel>
 */
final class FileRepository extends ParentRepository
{
    protected $fieldSearchable = [
        // 'id' => '=',
    ];
}

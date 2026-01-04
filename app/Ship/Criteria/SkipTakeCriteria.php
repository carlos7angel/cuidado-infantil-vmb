<?php

namespace App\Ship\Criteria;

use App\Ship\Parents\Criteria\Criteria as ParentCriteria;
use Prettus\Repository\Contracts\RepositoryInterface as PrettusRepositoryInterface;

class SkipTakeCriteria extends ParentCriteria
{
    public function __construct(
        private int $skip,
        private int $take,
    ) {
    }

    public function apply($model, PrettusRepositoryInterface $repository)
    {
        return $model->skip($this->skip)->take($this->take);
    }
}


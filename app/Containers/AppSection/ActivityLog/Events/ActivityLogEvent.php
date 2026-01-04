<?php

namespace App\Containers\AppSection\ActivityLog\Events;

use App\Ship\Parents\Events\Event as ParentEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class ActivityLogEvent extends ParentEvent
{
    public function __construct(
        private readonly string $log,
        private readonly array $request,
        private readonly mixed $model,
        private readonly ?array $data = null,
    ) {
    }

    public function getLog(): string
    {
        return $this->log;
    }

    public function getRequest(): array
    {
        return $this->request;
    }

    public function getModel(): mixed
    {
        return $this->model;
    }

    public function getData(): ?array
    {
        return $this->data;
    }
}   
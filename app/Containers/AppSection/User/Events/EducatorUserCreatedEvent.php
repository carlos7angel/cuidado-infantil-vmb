<?php

namespace App\Containers\AppSection\User\Events;

use App\Containers\AppSection\User\Mails\EducatorUserCreatedEmail;
use App\Containers\AppSection\User\Models\User;
use App\Containers\Monitoring\Educator\Models\Educator;
use App\Ship\Parents\Events\Event as ParentEvent;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class EducatorUserCreatedEvent extends ParentEvent implements ShouldQueue
{
    public function __construct(
        public readonly User $user,
        public readonly Educator $educator,
        public readonly string $password,
    ) {
    }

    public function handle()
    {
        Mail::send(new EducatorUserCreatedEmail($this->user, $this->educator, $this->password));
    }

    /**
     * @return Channel[]
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
<?php

namespace App\Containers\AppSection\User\Events;

use App\Containers\AppSection\User\Mails\AdminUserCreatedMail;
use App\Containers\AppSection\User\Models\User;
use App\Ship\Parents\Events\Event as ParentEvent;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

final class AdminUserCreatedEvent extends ParentEvent implements ShouldQueue
{
    public function __construct(
        public readonly User $user,
        public readonly string $password,
        public readonly bool $sendEmail = false,
    ) {
    }

    public function handle()
    {
        if (!$this->sendEmail) {
            return;
        }

        Mail::send(new AdminUserCreatedMail($this->user, $this->password));
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

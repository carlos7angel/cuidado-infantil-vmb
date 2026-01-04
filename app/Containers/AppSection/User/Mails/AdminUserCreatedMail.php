<?php

namespace App\Containers\AppSection\User\Mails;

use App\Containers\AppSection\User\Models\User;
use App\Ship\Parents\Mails\Mail as ParentMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

final class AdminUserCreatedMail extends ParentMail implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly User $user,
        public readonly string $password
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Habilitación de cuenta de Administrador - ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'appSection@user::adminUserCreated',
            with: [
                'user' => $this->user->load('childcareCenter'), // Load childcare center relationship
                'password' => $this->password,
                'loginUrl' => url('/admin/login'),
                'appName' => config('app.name'),
            ],
        );
    }
}

<?php

namespace App\Containers\AppSection\User\Mails;

use App\Containers\AppSection\User\Models\User;
use App\Ship\Parents\Mails\Mail as ParentMail;
use App\Containers\Monitoring\Educator\Models\Educator;

class EducatorUserCreatedEmail extends ParentMail
{
    public function __construct(
        public readonly User $user,
        public readonly Educator $educator,
        public readonly string $password
    ) {
    }

    public function build()
    {
        $url = route('web.server.info');
        return $this->view('appSection@user::educatorUserCreated')
            ->subject('Habilitación de cuenta | ' . config('app.name'))
            ->to($this->user->email, $this->user->name)
            ->with([
                'user' => $this->user,
                'educator' => $this->educator,
                'password' => $this->password,
                'url' => $url,
            ]);
    }
}
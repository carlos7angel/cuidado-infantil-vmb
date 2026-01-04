<?php

namespace App\Ship\Utils;

class HostHelper
{
    public function getHost(): string
    {
        $baseUrl = config('apiato.api.url');

        // Only add '/cuidado-infantil-vmb/public' for local environment
        if (config('app.env') === 'local') {
            $baseUrl .= '/cuidado-infantil-vmb/public';
        }

        return $baseUrl;
    }
}

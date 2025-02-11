<?php

namespace Modules\Authentication\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate;

class AuthenticateApi extends Authenticate
{
    protected function authenticate($request, array $guards): void
    {
        if (empty($guards)) {
            // Determine which authentication guard to use
            $guard = class_exists('Laravel\Passport\Passport') ? 'passport' : 'sanctum';
            $guards = [$guard];
        }

        parent::authenticate($request, $guards);
    }
}

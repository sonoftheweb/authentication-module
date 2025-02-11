<?php

namespace Modules\Authentication\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Authentication\Contracts\AuthenticationServiceInterface;

abstract class BaseAuthController extends Controller
{
    public function __construct(
        protected AuthenticationServiceInterface $authService
    ) {}
}

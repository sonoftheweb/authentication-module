<?php

namespace Modules\Authentication\Services;

use Modules\Authentication\Contracts\AuthenticationServiceInterface;
use Illuminate\Support\Facades\Auth;

class PassportAuthenticationService extends BaseAuthenticationService implements AuthenticationServiceInterface
{
    public function login(array $credentials): array
    {
        $user = $this->validateLogin($credentials);
        $token = $user->createToken('auth_token')->accessToken;

        return [
            'access_token' => $token,
            'token_type' => 'Bearer',
        ];
    }

    public function register(array $data): array
    {
        $user = $this->createUser($data);
        $response = [
            'message' => 'Registration successful. Please check your email to verify your account.',
        ];

        if (config('authentication.register_provides_token', true)) {
            $token = $user->createToken('auth_token')->accessToken;
            $response['access_token'] = $token;
            $response['token_type'] = 'Bearer';
        }

        return $response;
    }

    public function forgotPassword(string $email): bool
    {
        return $this->sendResetLink($email);
    }

    public function resetPassword(array $data): bool
    {
        return $this->updatePassword($data);
    }

    public function logout(): bool
    {
        /** @var \Modules\Authentication\Models\User $user */
        $user = Auth::user();
        
        // Revoke the token that was used to authenticate the current request
        $user->token()->revoke();
        
        return true;
    }
}

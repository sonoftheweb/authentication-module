<?php

namespace Modules\Authentication\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Modules\Authentication\Models\User;
use Modules\Authentication\Events\UserRegistered;
use Illuminate\Validation\ValidationException;

abstract class BaseAuthenticationService
{
    /**
     * Validate login credentials
     *
     * @param array $credentials
     * @return User
     * @throws ValidationException
     */
    protected function validateLogin(array $credentials): User
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (config('authentication.register_must_confirm_email', true) && !$user->hasVerifiedEmail()) {
            throw ValidationException::withMessages([
                'email' => ['Please verify your email address before logging in.'],
            ]);
        }

        return $user;
    }

    /**
     * Create a new user
     *
     * @param array $data
     * @return User
     */
    protected function createUser(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Dispatch the UserRegistered event
        event(new UserRegistered($user));

        if (config('authentication.register_must_confirm_email', true)) {
            $user->sendEmailVerificationNotification();
        }

        return $user;
    }

    /**
     * Send password reset link
     *
     * @param string $email
     * @return bool
     */
    protected function sendResetLink(string $email): bool
    {
        $status = Password::sendResetLink(['email' => $email]);
        return $status === Password::RESET_LINK_SENT;
    }

    /**
     * Reset user password
     *
     * @param array $data
     * @return bool
     */
    protected function updatePassword(array $data): bool
    {
        $status = Password::reset($data, function (User $user, string $password) {
            $user->password = Hash::make($password);
            $user->save();
        });

        return $status === Password::PASSWORD_RESET;
    }
}

<?php

namespace Modules\Authentication\Contracts;

interface AuthenticationServiceInterface
{
    /**
     * Login a user and return an access token
     *
     * @param array $credentials
     * @return array
     */
    public function login(array $credentials): array;

    /**
     * Register a new user and return an access token
     *
     * @param array $data
     * @return array
     */
    public function register(array $data): array;

    /**
     * Send password reset link to user's email
     *
     * @param string $email
     * @return bool
     */
    public function forgotPassword(string $email): bool;

    /**
     * Reset user's password
     *
     * @param array $data
     * @return bool
     */
    public function resetPassword(array $data): bool;

    /**
     * Logout user and revoke token
     *
     * @return bool
     */
    public function logout(): bool;
}

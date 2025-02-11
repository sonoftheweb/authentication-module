<?php

namespace Modules\Authentication\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Modules\Authentication\Http\Requests\RegisterRequest;

/**
 * @group Authentication
 *
 * APIs for managing user authentication
 */
class RegisterController extends BaseAuthController
{
    /**
     * Register User
     * 
     * Create a new user account and return an access token.
     * 
     * @unauthenticated
     * 
     * @bodyParam name string required The name of the user. Example: John Doe
     * @bodyParam email string required The email address of the user. Example: user@example.com
     * @bodyParam password string required The password for the account. Must be at least 8 characters. Example: password123
     * @bodyParam password_confirmation string required The password confirmation. Must match password. Example: password123
     * 
     * @response 200 scenario="Success" {
     *     "message": "Registration successful. Please check your email to verify your account.",
     *     "access_token": "1|abcdef123456",
     *     "token_type": "Bearer"
     * }
     * 
     * @response 422 scenario="Validation Error" {
     *     "message": "The given data was invalid.",
     *     "errors": {
     *         "email": [
     *             "The email has already been taken."
     *         ]
     *     }
     * }
     */
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register($request->validated());

        return response()->json($result);
    }
}

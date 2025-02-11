<?php

namespace Modules\Authentication\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Modules\Authentication\Http\Requests\LoginRequest;

/**
 * @group Authentication
 *
 * APIs for managing user authentication
 */
class LoginController extends BaseAuthController
{
    /**
     * Login User
     * 
     * Authenticate a user with their email and password.
     * 
     * @unauthenticated
     * 
     * @bodyParam email string required The email address of the user. Example: user@example.com
     * @bodyParam password string required The password of the user. Example: password123
     * 
     * @response 200 scenario="Success" {
     *     "access_token": "1|abcdef123456",
     *     "token_type": "Bearer"
     * }
     * 
     * @response 422 scenario="Validation Error" {
     *     "message": "The provided credentials are incorrect.",
     *     "errors": {
     *         "email": [
     *             "The provided credentials are incorrect."
     *         ]
     *     }
     * }
     */
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login($request->validated());

        return response()->json($result);
    }
}

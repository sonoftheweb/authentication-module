<?php

namespace Modules\Authentication\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Modules\Authentication\Http\Requests\ResetPasswordRequest;

/**
 * @group Authentication
 *
 * APIs for managing user authentication
 */
class ResetPasswordController extends BaseAuthController
{
    /**
     * Reset Password
     * 
     * Reset the user's password using the token from the reset link.
     * 
     * @unauthenticated
     * 
     * @bodyParam token string required The password reset token received by email. Example: 1234567890abcdef
     * @bodyParam email string required The email address of the user. Example: user@example.com
     * @bodyParam password string required The new password. Must be at least 8 characters. Example: newpassword123
     * @bodyParam password_confirmation string required The password confirmation. Must match password. Example: newpassword123
     * 
     * @response 200 scenario="Success" {
     *     "message": "Password reset successfully"
     * }
     * 
     * @response 400 scenario="Error" {
     *     "message": "Unable to reset password"
     * }
     * 
     * @response 422 scenario="Validation Error" {
     *     "message": "The given data was invalid.",
     *     "errors": {
     *         "token": [
     *             "This password reset token is invalid."
     *         ]
     *     }
     * }
     */
    public function __invoke(ResetPasswordRequest $request): JsonResponse
    {
        $success = $this->authService->resetPassword($request->validated());

        return response()->json([
            'message' => $success
                ? 'Password reset successfully'
                : 'Unable to reset password'
        ], $success ? 200 : 400);
    }
}

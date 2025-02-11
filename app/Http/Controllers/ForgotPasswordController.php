<?php

namespace Modules\Authentication\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Modules\Authentication\Http\Requests\ForgotPasswordRequest;

/**
 * @group Authentication
 *
 * APIs for managing user authentication
 */
class ForgotPasswordController extends BaseAuthController
{
    /**
     * Send Password Reset Link
     * 
     * Send a password reset link to the user's email address.
     * 
     * @unauthenticated
     * 
     * @bodyParam email string required The email address of the user. Example: user@example.com
     * 
     * @response 200 scenario="Success" {
     *     "message": "Password reset link sent successfully"
     * }
     * 
     * @response 400 scenario="Error" {
     *     "message": "Unable to send password reset link"
     * }
     * 
     * @response 422 scenario="Validation Error" {
     *     "message": "The given data was invalid.",
     *     "errors": {
     *         "email": [
     *             "The selected email is invalid."
     *         ]
     *     }
     * }
     */
    public function __invoke(ForgotPasswordRequest $request): JsonResponse
    {
        $success = $this->authService->forgotPassword($request->email);

        return response()->json([
            'message' => $success
                ? 'Password reset link sent successfully'
                : 'Unable to send password reset link'
        ], $success ? 200 : 400);
    }
}

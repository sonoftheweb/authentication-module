<?php

namespace Modules\Authentication\Http\Controllers;

use Illuminate\Http\JsonResponse;

/**
 * @group Authentication
 *
 * APIs for managing user authentication
 */
class LogoutController extends BaseAuthController
{
    /**
     * Logout User
     * 
     * Invalidate the user's access token.
     * 
     * @authenticated
     * 
     * @response 200 scenario="Success" {
     *     "message": "Successfully logged out"
     * }
     * 
     * @response 400 scenario="Error" {
     *     "message": "Unable to logout"
     * }
     * 
     * @response 401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function __invoke(): JsonResponse
    {
        $success = $this->authService->logout();

        return response()->json([
            'message' => $success
                ? 'Successfully logged out'
                : 'Unable to logout'
        ], $success ? 200 : 400);
    }
}

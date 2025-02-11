<?php

namespace Modules\Authentication\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Hash;

/**
 * @group Authentication
 *
 * APIs for managing user authentication
 */
class VerifyEmailController extends BaseAuthController
{
    /**
     * Verify Email
     * 
     * Verify the user's email address using the verification link.
     * 
     * @urlParam id integer required The ID of the user. Example: 1
     * @urlParam hash string required The verification hash from the email. Example: 3d4f2b10
     * 
     * @response 200 scenario="Success" {
     *     "message": "Email verified successfully"
     * }
     * 
     * @response 400 scenario="Already Verified" {
     *     "message": "Email already verified"
     * }
     */
    public function verify(Request $request, $id, $hash): JsonResponse
    {
        $user = User::findOrFail($id);

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified'], 400);
        }

        if (!hash_equals(
            sha1($user->getEmailForVerification()),
            $hash
        )) {
            return response()->json(['message' => 'Invalid verification link'], 400);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return response()->json(['message' => 'Email verified successfully']);
    }

    /**
     * Resend Email Verification Link
     * 
     * Resend the email verification link to the user.
     * 
     * @response 200 scenario="Success" {
     *     "message": "Verification link sent"
     * }
     * 
     * @response 400 scenario="Already Verified" {
     *     "message": "Email already verified"
     * }
     */
    public function resend(Request $request): JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified'], 400);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification link sent']);
    }
}

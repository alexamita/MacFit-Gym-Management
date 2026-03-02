<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;

class VerifyEmailContoller extends Controller
{
    /**
     * Handle the email verification link click.
     * * @param Request $request
     * @param int $id The user's unique ID from the URL
     * @param string $hash The security hash from the URL
     */
    public function verifyEmail(Request $request, $id, $hash)
    {
       // 1. Retrieve the user by ID or fail with a 404 if not found
        $user = User::findOrFail($id);

        // 2. Security Check: Compare the hash from the URL with the actual user's email hash
        // hash_equals is used here to prevent timing attacks.
        if (!$user || !hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json([
                'message' => 'Invalid verification link.'
                ], 400);
        }
        // 3. Check if the user has already completed this process: already verified
        if($user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email already verified.'
                ], 200);
        }
        // 4. Update the 'email_verified_at' timestamp in the database
        $user->markEmailAsVerified();

        // 5. Fire the standard Laravel Verified event
        // This allows other parts of your app (like welcome emails) to react to this verification.
        if ($user instanceof MustVerifyEmail) {
            event(new Verified($user));
        }

        // 6. Custom Logic: Activate the user account upon successful email verification
        $user->is_active = true; 
        $user->save();
        return response()->json(['message' => 'Email verified successfully.'], 200);
    }
}

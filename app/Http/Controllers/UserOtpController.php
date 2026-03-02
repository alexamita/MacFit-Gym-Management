<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserOtpController extends Controller
{
    /**
     * Verify the One-Time Password and log the user in.
     */
    public function verifyOtp(Request $request){
        // 1. Validate incoming request data
        $request->validate([
            'email'=> 'required|email',
            'otp'=> 'string',
        ]);

        // 2. Locate the user by the provided email
        $user = User::where("email", $request->email)->first();

        // If user doesn't exist, return a 400 error
        if(!$user){
            return response()->json([
                'message'=>'Invalid or expired OTP'
            ],400);
        }

        // 3. Retrieve the most recent OTP record for this user
        // This currently checks for a plain-text match in the DB
        $otpEntry = UserOtp::where('user_id', $user->id)
                    ->where('otp', $request->otp)
                    ->latest()
                    ->first();

        // 4. Validate OTP entry:
        // - Ensure an OTP record exists for the user
        // - Ensure the OTP has not expired (using a custom isExpired() method in the model)
        if(!$otpEntry || $otpEntry->isExpired()){
            return response()->json([
                'message' => 'Invalid or expired OTP'
            ], 400);
        }

         // 5. Success: Consume the OTP so it cannot be used again
        $otpEntry->delete();

       // 6. Security: Revoke any existing Sanctum tokens to ensure a fresh session
        $user->tokens()->delete();

        // 7. Generate a new plain-text API token for the user
        $token = $user->createToken('gym-token')->plainTextToken;

        // 8. Return success response with the bearer token and user details
        $user->is_active = true;
        $user->markEmailAsVerified();
        $user->save();
        return response()->json([
            'message'=> 'Login Successful',
            'token'=> $token,
            'user'=>$user,
        ], 201);
    }
}

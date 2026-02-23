<?php
// This controller handles user authentication, including registration, login, and logout functionalities.

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;

class AuthController extends Controller
{
    // 1. USER REGISTRATION
    public function register(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:40',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:4|max:15|confirmed',
            'user_image' => 'nullable|max:2048|mimes:jpeg,png,jpg',
            'role_id' => 'nullable|exists:roles,id'
        ]);

        // Check if a role_id is provided in the request, if not, assign the default role (e.g., 'USER') to the new user
        if($request->role_id) {
            $role_id = $request->role_id;
        } else {
            // Fetch the default role (e.g., 'USER') from the database to assign to the new user if no role_id is provided in the request
            $role = Role::where('name', 'USER')->first();
            $role_id = $role ? $role->id : null; // Added a check in case 'USER' role doesn't exist
        }
        // // Fetch the default role (e.g., 'USER') from the database to assign to the new user
        // $role = Role::where('name', 'USER')->first();

        // Create a new user with the validated data
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'role_id' => $role_id,
            // Hash the password before storing it in the database
            // 'password'=> Hash::make($validatedData['password']),
            // Alternative way to hash the password using bcrypt function
            'password' => bcrypt($validatedData['password']),
        ]);

        // Handle the user image upload if provided
        if ($request->hasFile('user_image')) {
            // Store the uploaded image in the 'users' directory within the 'public' disk and get the path
            $imagePath = $request->file('user_image')->store('users', 'public');
            // Update the user's profile with the image path if an image was uploaded
            $user->user_image = $imagePath;
        } else {
            // Set to null if no image is uploaded
            $user->user_image = null;
        }

        try {
            $user->save();
            // Generate a signed URL for email verification and send the verification email to the user using a custom notification class
            $signedUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->getEmailForVerification())]
        );
        // Send the verification email to the user using a custom notification class that accepts the signed URL as a parameter to include in the email content
        $user->notify(new VerifyEmailNotification($signedUrl));

            return response()->json([
                'message' => 'User registered successfully',
                'user' => $user
            ], 201);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Failed to register user',
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    // 2. USER LOGIN
    public function login(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:4|max:15',
        ]);

        // Find the user by email in the database
        $user = User::where('email', $validatedData['email'])->first();

        // If user exists and password is correct
        if ($user && Hash::check($validatedData['password'], $user->password)) {

            if (!$user->is_active) {
                return response()->json([
                    'message' => 'Your account is not active. Please verify your email address before logging in.'
                ], 403);
            }
            //
            $token = $user->createToken('auth_token')->plainTextToken;

            // Return the token and user information in the response
            return response()->json([
                'message' => 'Login successful',
                'token' => $token,
                // Include user information and their abilities (permissions) in the response
                'user' => $user,
                'abilities' => $user->abilities(),
            ], 200);
        }
        // If authentication fails, return an error response
        return response()->json([
            'error' => 'Invalid credentials'
        ], 401);
    }

    // 3. USER LOGOUT
    public function logout(Request $request)
    {
        // Revoke the token that was used to authenticate the current request
        $request->user()->currentAccessToken()->delete();

        // Return a success message in the response
        return response()->json([
            'message' => 'Logout successful'
        ], 200);
    }

    // DELETE USER
    public function deleteUser(Request $request, User $user)
    {
        // Authorize the user to ensure they have permission to delete their account
        $this->authorize('delete', $user);

        try {
            // Delete the user from the database
            $user->delete();
            return response()->json([
                'message' => 'User deleted successfully'
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Failed to delete user',
                'message' => $exception->getMessage()
            ], 500);
        }
    }
}

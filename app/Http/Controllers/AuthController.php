<?php
// This controller handles user authentication, including registration, login, and logout functionalities.

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // USER REGISTRATION
    public function register(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:40',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:4|max:15|confirmed',
            'user_image' => 'nullable|max:2048|mimes:jpeg,png,jpg'
        ]);

        // Fetch the default role (e.g., 'USER') from the database to assign to the new user
        $role = Role::where('name', 'USER')->first();

        // Create a new user with the validated data
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'role_id' => $role->id, // Assign the default role to the user

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

    // USER LOGIN
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
            // Generate a token for the user
            $token = $user->createToken('auth_token')->plainTextToken;

            // Return the token and user information in the response
            return response()->json([
                'message' => 'Login successful',
                'token' => $token,
                'user' => $user,
                'abilities' => $user->abilities(),
            ], 200);
        }
        // If authentication fails, return an error response
        return response()->json([
            'error' => 'Invalid credentials'
        ], 401);
    }

    // USER LOGOUT
    public function logout(Request $request)
    {
        // Revoke the token that was used to authenticate the current request
        $request->user()->currentAccessToken()->delete();

        // Return a success message in the response
        return response()->json([
            'message' => 'Logout successful'
        ], 200);
    }
}

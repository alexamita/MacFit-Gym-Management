<?php

namespace App\Http\Controllers;

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
        ]);

        // Create a new user with the validated data
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            // Hash the password before storing it in the database
            'password' => bcrypt($validatedData['password']),
        ]);

        // Assign a default role if needed (example: 'member')
        // $user->assignRole($validatedData['role']);
        try{
            $user->save();
            return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
        }
        catch(\Exception $exception){
            return response()->json([
                'error'=>'Failed to register user',
                'message'=>$exception->getMessage()
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

        // Find the user by email
        $user = User::where('email', $validatedData['email'])->first();

        // If user exists and password is correct
        if ($user && Hash::check($validatedData['password'], $user->password))
            {
                // Generate a token for the user
                $token = $user->createToken('auth_token')->plainTextToken;
                
                // Return the token and user information in the response
                return response()->json([
                    'message' => 'Login successful',
                    'token' => $token,
                    'user' => $user
                ], 200);
            }
        // If authentication fails, return an error response
        return response()->json([
            'error' => 'Invalid credentials'
        ], 401);
    }
}

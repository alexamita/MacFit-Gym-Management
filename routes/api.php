<?php
// API routes for the gym management system, defining endpoints for user registration and login, as well as protected routes for managing roles, categories, gyms, bundles, equipment, and subscriptions that require authentication to access and perform CRUD operations on the respective resources in the application

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResendEmailVerificationEcontoller;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GymController;
use App\Http\Controllers\BundleController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\VerifyEmailEcontoller;
use Illuminate\Support\Facades\Route;

// 1. PUBLIC ROUTES
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Email verification routes
Route::get('/email/verify/{id}/{hash}', [VerifyEmailEcontoller::class, 'verifyEmail'])
->name('verification.verify')
->middleware(['signed', 'throttle:6,1']); // Throttle to prevent abuse (6 attempts per minute)

Route::post('/email/resend', [ResendEmailVerificationEcontoller::class, 'resendVerificationEmail'])
->middleware('throttle:6,1'); // Throttle to prevent abuse (6 attempts per minute)

// 2. PROTECTED ROUTES
// All routes within this group require authentication using Sanctum middleware, ensuring that only authenticated users can access the endpoints for managing roles, categories, gyms, bundles, equipment, and subscriptions in the gym management system application

Route::middleware('auth:sanctum')->group(function () {
// Logout route for authenticated users to revoke their access token and log out of the application
    Route::post('/logout', [AuthController::class, 'logout']);

    // Route to delete a user, allowing authenticated users to remove their account from the system
    Route::delete('/deleteUser', [AuthController::class, 'deleteUser']);

    // Role Routes
    Route::post('/saveRole', [RoleController::class, 'createRole']);
    Route::get('/getRoles', [RoleController::class, 'readAllRoles']);
    Route::get('/getRole/{role}', [RoleController::class, 'readRole']);
    Route::post('/updateRole/{role}', [RoleController::class, 'updateRole']);
    Route::delete('/deleteRole/{role}', [RoleController::class, 'deleteRole']);

    // Category Routes
    Route::post('/saveCategory', [CategoryController::class, 'createCategory']);
    Route::get('/getCategories', [CategoryController::class, 'readAllCategories']);
    Route::get('/getCategory/{category}', [CategoryController::class, 'readCategory']);
    Route::post('/updateCategory/{category}', [CategoryController::class, 'updateCategory']);
    Route::delete('/deleteCategory/{category}', [CategoryController::class, 'deleteCategory']);

    // Gym Routes
    Route::post('/saveGym', [GymController::class, 'createGym']);
    Route::get('/getGyms', [GymController::class, 'readAllGyms']);
    Route::get('/getGym/{gym}', [GymController::class, 'readGym']);
    Route::post('/updateGym/{gym}', [GymController::class, 'updateGym']);
    Route::delete('/deleteGym/{gym}', [GymController::class, 'deleteGym']);

    // Bundle Routes
    Route::post('/saveBundle', [BundleController::class, 'createBundle']);
    Route::get('/getBundles', [BundleController::class, 'readAllBundles']);
    Route::get('/getBundle/{bundle}', [BundleController::class, 'readBundle']);
    Route::post('/updateBundle/{bundle}', [BundleController::class, 'updateBundle']);
    Route::delete('/deleteBundle/{bundle}', [BundleController::class, 'deleteBundle']);

    // Equipment Routes
    Route::post('/saveEquipment', [EquipmentController::class, 'createEquipment']);
    Route::get('/getEquipment', [EquipmentController::class, 'readAllEquipment']);
    Route::get('/getEquipment/{equipment}', [EquipmentController::class, 'readEquipment']);
    Route::post('/updateEquipment/{equipment}', [EquipmentController::class, 'updateEquipment']);
    Route::delete('/deleteEquipment/{equipment}', [EquipmentController::class, 'deleteEquipment']);

    // Subscription routes
    Route::post('/saveSubscription', [SubscriptionController::class, 'createSubscription']);
    Route::get('/getSubscriptions', [SubscriptionController::class, 'readAllSubscriptions']);
    Route::get('/getSubscription/{subscription}', [SubscriptionController::class, 'readSubscription']);
    Route::delete('/deleteSubscription/{subscription}', [SubscriptionController::class, 'deleteSubscription']);
});

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gym;
class GymController extends Controller
{
    // Create gym
    public function createGym(Request $request){
        // Authorize using the GymPolicy 'create' method
        $this->authorize('create', Gym::class);

        // Validation rules based on the migration and seeder logic
        $validated = $request->validate([
            'name'=>'required|string',
            'longitude'=>'required|numeric|between:-180,180',
            'latitude'=>'required|numeric|between:-90,90',
            'description'=>'string|max:1000',
            ]);

        try {
            $gym = Gym::create($validated);
            return response()->json($gym, 201);
        } catch (\Exception $exception) {
            return response()->json([
                'error'   => 'Failed to save gym',
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    // Read all gyms
    public function readAllGyms(){
        // Authorize using the GymPolicy - everyone can view gyms, but we check just in case.
        $this->authorize('viewAny', Gym::class);

        try {
            return response()->json(Gym::all());
        } catch (\Exception $exception) {
            return response()->json([
                'error'   => 'Failed to fetch gyms',
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    // Read a single gym
    public function readGym(Gym $gym){
        // Route Model Binding handles findOrFail and 404 automatically
        $this->authorize('view', $gym);
       // Load related data like equipment or bundles
        return response()->json($gym->load(['equipment', 'bundles']));
    }

    // Update an existing gym
    public function updateGym(Request $request, Gym $gym){
        // Authorize using the GymPolicy 'update' method - Only Admin or GYM_MANAGER
        $this->authorize('update', $gym);

        $validated = $request->validate([
                'name'=>'required|string',
                'longitude'=>'required|numeric|between:-180,180',
                'latitude'=>'required|numeric|between:-90,90',
                'description'=>'string|max:1000',
            ]);

        try {
            $gym->update($validated);
            return response()->json($gym);
        } catch (\Exception $exception) {
            return response()->json([
                'error'   => 'Failed to update the gym with ID: ' . $gym->id,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    // Delete gym
    public function deleteGym(Gym $gym){
        // Authorize (Only Admin)
        $this->authorize('delete', $gym);

        try {
            $gym->delete();
            return response()->json(['message' => 'Gym deleted successfully'], 200);
        }
        catch (\Exception $exception) {
            return response()->json([
                'error'   => 'Failed to delete the gym',
                'message' => $exception->getMessage()
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bundle;
use App\Models\Role;

class BundleController extends Controller
{
    // Create bundle
    public function createBundle(Request $request){
        // Authorize using the BundlePolicy 'create' method
        $this->authorize('create', Bundle::class);

        $validated = $request->validate([
            // Validation rules based on the migration and seeder logic
            'name'=>'required|string',
            'location'=>'required|string',
            'start_time'=>'required|date_format:H:i', // Expecting time in HH:MM format
            'session_duration'=>'required|integer',
            'value'=> 'required|integer',
            'description'=>'string|nullable|max:1000',
            'category_id'=>'int|exists:categories,id',
            'gym_id'=>'int|exists:gyms,id',
            ]);

            // Save the bundle and return a response
            try{
            // Using Mass Assignment to create the bundle
            $bundle = Bundle::create($validated);
            return response()->json($bundle, 201);;
            }
            // Handle any exceptions that may occur during the save operation
            catch(\Exception $exception){
                return response()->json(
                    ['error'=>'Failed to save bundle',
                    'message'=> $exception->getMessage()
                ], 500);
            }
    }

    // Read all bundles
    public function readAllBundles(){
        // Authorize using the BundlePolicy 'viewAny' method: everyone can view bundles, but we check just in case
        $this->authorize('viewAny', Bundle::class);

        // Fetch bundles with related category and gym names for better readability
            try{
                // Using Eager Loading (with) is much cleaner than manual joins
                $bundles = Bundle::with(['category', 'gym'])->get();

                /* join bundles with category and gym names
                $bundles = Bundle::join('categories', 'bundles.category_id', '=', 'categories.id')
                ->join('gyms', 'bundles.gym_id', '=', 'gyms.id')
                ->select('bundles.*', 'categories.name as category_name', 'gyms.name as gym_name')
                ->get();
                */

                return response()->json($bundles);
            }
            catch(\Exception $exception){
                return response()->json([
                    'error'=> 'Failed to fetch bundles',
                    'message'=> $exception->getMessage()
                ], 500);
            }
    }

    // Read single bundle
    public function readBundle($id){
        // Fetch the bundle first to check if it exists and to use it for authorization
        $bundle = Bundle::findOrFail($id);
        // Authorize using the BundlePolicy 'view' method
        $this->authorize('view', $bundle);
        return response()->json($bundle);
    }

    // Update bundle
    public function updateBundle(Request $request, $id){
        // Authorize using the BundlePolicy 'update' method
        $bundle = Bundle::findOrFail($id);
        $this->authorize('update', $bundle);

        $validated = $request->validate([
            'name'=>'required|string',
            'location'=>'required|string',
            'start_time'=>'required|date_format:H:i', // Expecting time in HH:MM format
            'session_duration'=>'required|integer',
            'value'=> 'required|integer',
            'description'=>'string|nullable|max:1000',
            'category_id'=>'int|exists:categories,id',
            'gym_id'=>'int|exists:gyms,id',
            ]);

        try{
            $bundle->update($validated);
            return response()->json($bundle);
        }
        catch(\Exception $exception){
            return response()->json([
            'error'=> 'Failed to update the bundle with ID: '.$id,
            'message'=> $exception->getMessage()
            ], 500);
        }
    }

    // Delete bundle
    public function deleteBundle($id){
        // Authorize using the BundlePolicy 'delete' method
        $bundle = Bundle::findOrFail($id);
        $this->authorize('delete', $bundle);
        try{
            $bundle->delete();
            return response()->json(['message' => 'Bundle deleted successfully']);
        }
        catch(\Exception $exception){
            return response()->json([
                'error'=> 'Failed to delete the bundle',
                'message'=> $exception->getMessage()
            ], 500);
        }
    }
}

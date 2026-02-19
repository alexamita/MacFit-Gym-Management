<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bundle;
class BundleController extends Controller
{
      // Create bundle
    public function createBundle(Request $request){
        $validated = $request->validate([
            // Validation rules based on the migration and seeder logic
            'name'=>'required|string',
            'location'=>'required|string',
            'start_time'=>'required|date_format:H:i', // Expecting time in HH:MM format
            'session_duration'=>'required|integer',
            'description'=>'string|nullable|max:1000',
            'category_id'=>'int|exists:categories,id',
            'gym_id'=>'int|exists:gyms,id',
            ]);

            // Create a new bundle instance and save to the database
            $bundle = new Bundle();
            $bundle->name = $validated['name'];
            $bundle->location = $validated['location'];
            $bundle->start_time = $validated['start_time'];
            $bundle->session_duration = $validated['session_duration'];
            $bundle->description = $validated['description'];
            $bundle->category_id = $validated['category_id'];
            $bundle->gym_id = $validated['gym_id'];

            // Save the bundle and return a response
            try{
                $createdBundle = $bundle->save();
                return response()->json($bundle);
            }
            // Handle any exceptions that may occur during the save operation
            catch(\Exception $exception){
                return response()->json(
                    ['error'=>'Failed to save bundle',
                    'message'=> $exception->getMessage()
                ]);
            }
    }

    // Read all categories
    public function readAllBundles(){
        // Fetch bundles with related category and gym names for better readability
            try{
                // $bundles = Bundle::all();
                // join bundles with category and gym names
                $bundles = Bundle::join('categories', 'bundles.category_id', '=', 'categories.id')
                ->join('gyms', 'bundles.gym_id', '=', 'gyms.id')
                ->select('bundles.*', 'categories.name as category_name', 'gyms.name as gym_name')
                ->get();

                return response()->json($bundles);
            }
            // Handle any exceptions that may occur during the database query
            catch(\Exception $exception){
                return response()->json([
                    'error'=> 'Failed to fetch bundles',
                    'message'=> $exception->getMessage()
                ]);
            }
    }

    public function readBundle($id){
        try {
            $bundle = Bundle::findOrFail($id);
            return response()->json($bundle);
        }
        catch(\Exception $exception){
                return response()->json([
                    'error'=> 'Failed to fetch the bundle with ID: ',&$id,
                    'message'=> $exception->getMessage()
                ]);
            }
    }

    // Update bundle
    public function updateBundle(Request $request, $id){
        $validated = $request->validate([
            'name'=>'required|string',
            'location'=>'required|string',
            'start_time'=>'required|date_format:H:i', // Expecting time in HH:MM format
            'session_duration'=>'required|integer',
            'description'=>'string|nullable|max:1000',
            'category_id'=>'int|exists:categories,id',
            'gym_id'=>'int|exists:gyms,id',
            ]);

        try{
            $bundle = Bundle::findOrFail($id);
            $bundle->name = $validated['name'];
            $bundle->location = $validated['location'];
            $bundle->start_time = $validated['start_time'];
            $bundle->session_duration = $validated['session_duration'];
            $bundle->description = $validated['description'];
            $bundle->category_id = $validated['category_id'];
            $bundle->gym_id = $validated['gym_id'];
            $bundle->save();
            return response()->json($bundle);
        }
        catch(\Exception $exception){
            return response()->json([
            'error'=> 'Failed to fetch the bundle with ID: '.$id,
            'message'=> $exception->getMessage()
            ]);
        }
    }

    // Delete bundle
    public function deleteBundle($id){
        try{
            $bundle = Bundle::findOrFail($id);
            $bundle->delete();
            return response("Bundle deleted successfully");
        }
        catch(\Exception $exception){
            return response()->json([
                'error'=> 'Failed to delete the bundle',
                'message'=> $exception->getMessage()
            ]);
        }
    }
}

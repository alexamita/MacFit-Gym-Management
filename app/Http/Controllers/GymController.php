<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gym;
class GymController extends Controller
{
    // Create gym
    public function createGym(Request $request){
        $validated = $request->validate([
            'name'=>'required|string',
            'longitude'=>'required|numeric|between:-180,180',
            'latitude'=>'required|numeric|between:-90,90',
            'description'=>'string|max:1000',
            ]);
            $gym = new Gym();
            $gym->name = $validated['name'];
            $gym->longitude = $validated['longitude'];
            $gym->latitude = $validated['latitude'];
            $gym->description = $validated['description'];

            try{
                $createdGym = $gym->save();
                return response()->json($gym);
            }
            catch(\Exception $exception){
                return response()->json(
                    ['error'=>'Failed to save gym',
                    'message'=> $exception->getMessage()
                ]);
            }
    }

    // Read all gyms
    public function readAllGyms(){
        try{
            $categories = Gym::all();
            return response()->json($categories);
            }
            catch(\Exception $exception){
                return response()->json([
                    'error'=> 'Failed to fetch categories',
                    'message'=> $exception->getMessage()
                ]);
            }
    }

    // Read single gym
    public function readGym($id){
        try {
            $gym = Gym::findOrFail($id);
            return response()->json($gym);
        }
        catch(\Exception $exception){
                return response()->json([
                    'error'=> 'Failed to fetch the gym with ID: ',&$id,
                    'message'=> $exception->getMessage()
                ]);
            }
    }

    // Update gym
    public function updateGym(Request $request, $id){
        $validated = $request->validate([
                'name'=>'required|string',
                'longitude'=>'required|numeric|between:-180,180',
                'latitude'=>'required|numeric|between:-90,90',
                'description'=>'string|max:1000',
            ]);

        try{
            $gym = Gym::findOrFail($id);
            $gym->name = $validated['name'];
            $gym->longitude = $validated['longitude'];
            $gym->latitude = $validated['longitude'];
            $gym->description = $validated['description'];
            $gym->save();
            return response()->json($gym);
        }
        catch(\Exception $exception){
            return response()->json([
            'error'=> 'Failed to fetch the gym with ID: '.$id,
            'message'=> $exception->getMessage()
            ]);
        }
    }

    // Delete gym
    public function deleteGym($id){
        try{
            $gym = Gym::findOrFail($id);
            $gym->delete();
            return response("Gym deleted successfully");
        }
        catch(\Exception $exception){
            return response()->json([
                'error'=> 'Failed to delete the gym',
                'message'=> $exception->getMessage()
            ]);
        }
    }
}

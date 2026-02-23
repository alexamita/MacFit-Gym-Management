<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipment;
class EquipmentController extends Controller
{
// Create equipment
    public function createEquipment(Request $request){
        // Authorize using the EquipmentPolicy 'create' method
        $this->authorize('create', Equipment::class);

        // Validation rules based on the migration and seeder logic
        $validated = $request->validate([
            'name'=>'required|string',
            'usage'=>'required|string',
            'model_no'=>'required|string|max:100',
            'value'=>'required|numeric|min:0',
            'status'=>'required|string|in:ACTIVE,UNDER_MAINTENANCE,FAULTY,DECOMMISSIONED',
            'gym_id'=>'required|int|exists:gyms,id',
            'category_id'=>'required|int|exists:categories,id',
            ]);

        try {
            // Using Mass Assignment
            $equipment = Equipment::create($validated);
            return response()->json($equipment, 201);
        }
        catch (\Exception $exception){
            return response()->json([
                'error'   => 'Failed to save equipment',
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    // Read all equipment
    public function readAllEquipment(){
        // Authorize using the EquipmentPolicy 'viewAny' method: everyone can view equipment, but we check just in case
        $this->authorize('viewAny', Equipment::class);
        try{
            // Eager load Gym and Category instead of using JOIN
            $equipment = Equipment::with(['gym:id,name', 'category:id,name'])->get();

            /*
            // read equipment with gym name and category name using join
            $equipment = Equipment::join('gyms', 'equipment.gym_id', '=', 'gyms.id')
            ->join('categories', 'equipment.category_id', '=', 'categories.id')
            ->select('equipment.*', 'gyms.name as gym_name', 'categories.name as category_name')
            ->get(); // fetch all equipment with gym name and category name
            */
            return response()->json($equipment);
            }
            catch(\Exception $exception){
                return response()->json([
                    'error'=> 'Failed to fetch equipment',
                    'message'=> $exception->getMessage()
                ]);
            }
    }

    // Read single equipment
    public function readEquipment(Equipment $equipment){
        // Route Model Binding handles findOrFail and 404 automatically
        $this->authorize('view', $equipment);

        // Eager load Gym and Category for better readability
        return response()->json($equipment->load(['gym', 'category']));
    }

    //Update the specified equipment in storage.
    public function updateEquipment(Request $request, Equipment $equipment){
        // Authorize using the EquipmentPolicy 'update' method
        $this->authorize('update', $equipment);

        $validated = $request->validate([
            'name'=>'required|string',
            'usage'=>'required|string',
            'model_no'=>'required|string|max:100',
            'value'=>'required|numeric|min:0',
            'status'=>'required|string|in:ACTIVE,UNDER_MAINTENANCE,FAULTY,DECOMMISSIONED',
            'gym_id'=>'required|int|exists:gyms,id',
            ]);

        try {
            $equipment->update($validated);
            return response()->json($equipment);
        } catch (\Exception $exception) {
            return response()->json([
                'error'   => 'Failed to update the equipment with ID: ' . $equipment->id,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    // Remove the specified equipment from storage.
    public function deleteEquipment(Equipment $equipment){
        // Authorize using the EquipmentPolicy 'delete' method
        $this->authorize('delete', $equipment);
        try{
            $equipment->delete();
            return response("Equipment deleted successfully", 200);
        }
        catch(\Exception $exception){
            return response()->json([
                'error'=> 'Failed to delete the equipment',
                'message'=> $exception->getMessage()
            ], 500);
        }
    }

}

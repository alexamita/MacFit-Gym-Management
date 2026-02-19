<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipment;
class EquipmentController extends Controller
{
// Create equipment
    public function createEquipment(Request $request){
        $validated = $request->validate([
            'name'=>'required|string',
            'usage'=>'required|string',
            'model_no'=>'required|string|max:100',
            'value'=>'required|numeric|min:0',
            'status'=>'required|string|in:ACTIVE,UNDER_MAINTENANCE,FAULTY,DECOMMISSIONED',
            'gym_id'=>'required|int|exists:gyms,id',
            ]);
            $equipment = new Equipment();
            $equipment->name = $validated['name'];
            $equipment->usage = $validated['usage'];
            $equipment->model_no = $validated['model_no'];
            $equipment->value = $validated['value'];
            $equipment->status = $validated['status'];
            $equipment->gym_id = $validated['gym_id'];

            try{
                $createdEquipment = $equipment->save();
                return response()->json($equipment);
            }
            catch(\Exception $exception){
                return response()->json(
                    ['error'=>'Failed to save equipment',
                    'message'=> $exception->getMessage()
                ]);
            }
    }

    // Read all equipment
    public function readAllEquipment(){
        try{
            // $equipment = Equipment::all();
            // read equipment with gym name
            $equipment = Equipment::join('gyms', 'equipment.gym_id', '=', 'gyms.id')
            ->select('equipment.*', 'gyms.name as gym_name')
            ->get();
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
    public function readEquipment($id){
        try {
            $equipment = Equipment::findOrFail($id);
            return response()->json($equipment);
        }
        catch(\Exception $exception){
                return response()->json([
                    'error'=> 'Failed to fetch the equipment with ID: ',&$id,
                    'message'=> $exception->getMessage()
                ]);
            }
    }

    //Update the specified equipment in storage.
    public function updateEquipment(Request $request, $id){
        $validated = $request->validate([
            'name'=>'required|string',
            'usage'=>'required|string',
            'model_no'=>'required|string|max:100',
            'value'=>'required|numeric|min:0',
            'status'=>'required|string|in:ACTIVE,UNDER_MAINTENANCE,FAULTY,DECOMMISSIONED',
            'gym_id'=>'required|int|exists:gyms,id',
            ]);

        try{
            $equipment = Equipment::findOrFail($id);
            $equipment->name = $validated['name'];
            $equipment->usage = $validated['usage'];
            $equipment->model_no = $validated['model_no'];
            $equipment->value = $validated['value'];
            $equipment->status = $validated['status'];
            $equipment->gym_id = $validated['gym_id'];
            $equipment->save();
            return response()->json($equipment);
        }
        catch(\Exception $exception){
            return response()->json([
            'error'=> 'Failed to fetch the equipment with ID: '.$id,
            'message'=> $exception->getMessage()
            ]);
        }
    }

    // Remove the specified equipment from storage.
    public function deleteEquipment($id){
        try{
            $equipment = Equipment::findOrFail($id);
            $equipment->delete();
            return response("Equipment deleted successfully");
        }
        catch(\Exception $exception){
            return response()->json([
                'error'=> 'Failed to delete the equipment',
                'message'=> $exception->getMessage()
            ]);
        }
    }

}

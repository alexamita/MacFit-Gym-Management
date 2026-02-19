<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
class RoleController extends Controller
{
    // Create role
    public function createRole(Request $request){
        $validated = $request->validate([
            'name'=>'required|string|unique:roles,name',
            'description'=>'nullable|string|max:1000',
            ]);
            $role = new Role();
            $role->name = $validated['name'];
            $role->description = $validated['description'];

            try{
                $createdRole = $role->save();
                return response()->json($role);
            }
            catch(\Exception $exception){
                return response()->json(
                    ['error'=>'Failed to save role',
                    'message'=> $exception->getMessage()
                ]);
            }
    }

    // Read all roles
    public function readAllRoles(){
        try{
            $roles = Role::all();
            return response()->json($roles);
            }
            catch(\Exception $exception){
                return response()->json([
                    'error'=> 'Failed to fetch roles',
                    'message'=> $exception->getMessage()
                ]);
            }
    }

    // Read single role
    public function readRole($id){
        try {
            $role = Role::findOrFail($id);
            return response()->json($role);
        }
        catch(\Exception $exception){
                return response()->json([
                    'error'=> 'Failed to fetch the role with ID: ',&$id,
                    'message'=> $exception->getMessage()
                ]);
            }
    }

    // Update role
    public function updateRole(Request $request, $id){
        try{
            $validated = $request->validate([
                'name'=>'required|string',
                'description'=>'nullable|string|max:1000',
            ]);
            $existingRole = Role::findOrFail($id);
            $existingRole->name = $validated['name'];
            $existingRole->description = $validated['description'];
            $existingRole->save();
            return response()->json($existingRole);
        }
        catch(\Exception $exception){
            return response()->json([
            'error'=> 'Failed to fetch the role with ID: '.$id,
            'message'=> $exception->getMessage()
            ]);
        }
    }

    // Delete role
    public function deleteRole($id){
        try{
            $role = Role::findOrFail($id);
            $role->delete();
            return response("Role deleted successfully");
        }
        catch(\Exception $exception){
            return response()->json([
                'error'=> 'Failed to delete the role',
                'message'=> $exception->getMessage()
            ]);
        }
    }
}

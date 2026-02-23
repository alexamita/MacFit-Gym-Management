<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
class RoleController extends Controller
{
    // Create role
    public function createRole(Request $request){
        // Authorize using the RolePolicy 'create' method - Only Admin can create roles (via Policy/Gate bypass).
        $this->authorize('create', Role::class);

        $validated = $request->validate([
            'name'=>'required|string|unique:roles,name',
            'description'=>'nullable|string|max:1000',
            ]);

        try {
            $role = Role::create($validated);
            return response()->json($role, 201);
        }
        catch (\Exception $exception) {
            return response()->json([
                'error'   => 'Failed to save role',
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    // Read all roles
    public function readAllRoles(){
        // Only Admin can view all roles (via Policy/Gate bypass).
        $this->authorize('viewAny', Role::class);
        try {
            return response()->json(Role::all());
        }
        catch (\Exception $exception) {
            return response()->json([
                'error'   => 'Failed to fetch roles',
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    // Read a specific role
    public function readRole(Role $role){
        // Route Model Binding handles findOrFail and 404 automatically
        $this->authorize('view', $role);

        return response()->json($role);
    }

    // Update role
    public function updateRole(Request $request, Role $role){
        // Authorize using the RolePolicy 'update' method - Not recommended to allow role updates, but Admin can, if needed.
        $this->authorize('update', $role);

        $validated = $request->validate([
            'name'        => 'required|string|unique:roles,name,' . $role->id,
            'description' => 'nullable|string|max:1000',
        ]);
        try {
            $role->update($validated);
            return response()->json($role);
        }
        catch (\Exception $exception) {
            return response()->json([
                'error'   => 'Failed to update the role with ID: ' . $role->id,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    // Delete role
    public function deleteRole(Role $role){
        $this->authorize('delete', $role);
        try{
            $role->delete();
            return response("Role deleted successfully", 200);
        }
        catch(\Exception $exception){
            return response()->json([
                'error'=> 'Failed to delete the role',
                'message'=> $exception->getMessage()
            ], 500);
        }
    }
}

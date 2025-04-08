<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;


class RoleController extends Controller
{
  

    // Get all roles
    public function index()
    {
        $roles = Role::all();
        return response()->json($roles);
    }

    // Create a new role
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles',
        ]);

        $role = Role::create([
            'name' => $request->name,
        ]);

        return response()->json($role, 201);
    }

    // Get a specific role
    public function show($id)
    {
        $role = Role::find($id);
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }
        return response()->json($role);
    }

    // Update a role
    public function update(Request $request, $id)
    {
        $role = Role::find($id);
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
        ]);

        $role->update([
            'name' => $request->name,
        ]);

        return response()->json($role);
    }

    // Delete a role
    public function destroy($id)
    {
        $role = Role::find($id);
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        $role->delete();
        return response()->json(['message' => 'Role deleted successfully']);
    }

}

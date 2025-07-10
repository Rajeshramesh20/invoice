<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Roles;
use Exception;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\storeMenuOrRole;
use App\Services\UserRoleMenuServices;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(UserRoleMenuServices  $RoleMenuServices )
    {
        try{
            $Roles = $RoleMenuServices->getAllRole();
            if($Roles){
                return response()->json([
                    'status' => true,
                    'message' => 'success',
                    'data' => $Roles ,
                ]);
            }
        }catch(Exception $e){

            return response()->json(['status' => false, 'message' => 'Server Error']);
        }
    }

    /**
     * Show the form for creating a new resource.
     */

    public function create(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(storeMenuOrRole $request, UserRoleMenuServices  $RoleMenuServices)
    {
        try {
            $user_role = auth()->user();
            $roleName = $user_role?->roles?->name;
            if ($roleName !== 'superadmin') {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized:only super admin can access.',
                ], 403);
            }

            $request->merge(['table' => 'roles']);

            $validated = $request->validated();

            $role = $RoleMenuServices->storeRole($validated);

            return response()->json([
                'status' => true,
                'data' => $role,
                'message' =>  'Role created successfully.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create Role',
            ], 404);
        }
    }

    /**
     * Display the specified resource.
     */

    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit(UserRoleMenuServices  $RoleMenuServices, $id)
    {
        try {
            $user = auth()->user();
            // if(!$user || $user->roles->name !== "superadmin"){
         
            $roleName = $user->role?->name;
            if ($roleName !== 'superadmin') {
                return response()->json([
                    'status' => false,
                    // 'rolename' => $roleName,   
                    'message' => 'Unauthorized:only super admin can access.',                    
                ], 403);
            }

            $edit_user_role = $RoleMenuServices->editRole($id);

            return response()->json([
                'status' => true,
                'data' => $edit_user_role,
            ]);
        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Role not found.',
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(storeMenuOrRole $request, string $id, UserRoleMenuServices  $RoleMenuServices)
    {
        try {
            $user_role = auth()->user()?->roles?->name;
            if ($user_role !== 'superadmin') {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized:only super admin can access.',
                ], 403);
            }
            $request->merge(['table' => 'roles', 'id' => $id]);
            $validated = $request->validated();
            $role = $RoleMenuServices->roleUpdate($validated, $id);

            return response()->json([
                'status' => true,
                'data' => $role,
                'message' => 'Role updated successfully.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Role not found.',
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(string $id, UserRoleMenuServices  $RoleMenuServices)
    {
        try {

            $user_role = auth()->user()?->roles?->name;
            if ($user_role !== 'superadmin') {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized:only super admin can access.',
                ], 403);
            }
            $RoleMenuServices->RoleDestroy($id);

            return response()->json([
                'status' => true,
                'message' => 'Role deleted successfully.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Role not found.',
            ], 404);
        }
    }
}

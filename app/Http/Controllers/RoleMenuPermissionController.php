<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoleMenuPermission;
use Exception;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\MenuPermissionRequest;
use App\Services\UserRoleMenuServices;

class RoleMenuPermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(UserRoleMenuServices $RoleMenuServices)
    {
        try {
            $RoleMenuPermission = $RoleMenuServices->getAllRoleMenuPermission();
            $roles= $RoleMenuServices->getAllRole();
            $menus= $RoleMenuServices->getAllmenu(); 
            if ($RoleMenuPermission) {
                return response()->json([
                    'status' => true,
                    'message' => 'success',
                    'data' => $RoleMenuPermission,
                    'roles'=> $roles,
                    'menus'=>$menus
                ]);
            }
        } catch (Exception $e) {

            return response()->json(['status' => false, 'message' => 'Server Error']);
        } 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MenuPermissionRequest $request, UserRoleMenuServices $RoleMenuServices )
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

            $validated = $request->validated();

            $roleMenuPermission = $RoleMenuServices->RoleMenuPermission($validated);

            return response()->json([
                'status' => true,
                'data' => $roleMenuPermission,
                'message' => 'Menu permission created successfully.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create Menu permission.',
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
    public function edit(string $id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
    //    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, UserRoleMenuServices $RoleMenuServices)
    {

        try {

            $user_role = auth()->user()?->roles?->name;
            if ($user_role !== 'superadmin') {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized:only super admin can access.',
                ], 403);
            }
            $RoleMenuServices->RoleMenuPermissionDestroy($id);

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


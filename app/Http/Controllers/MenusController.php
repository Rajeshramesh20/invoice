<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Exception;
use App\Http\Requests\storeMenuOrRole;

use App\Services\UserRoleMenuServices;

class MenusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(UserRoleMenuServices $RoleMenuservices)
    {
        try {
            $Menus = $RoleMenuservices->getAllMenu();
            if ($Menus) {
                return response()->json([
                    'status' => true,
                    'message' => 'success',
                    'data' => $Menus,
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
    public function store(storeMenuOrRole $request, UserRoleMenuServices $RoleMenuservices)
    {
        try {

            $user_role = auth()->user()?->roles?->name;
            if ($user_role !== 'superadmin') {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized:only super admin can access.',
                ], 403);
            }
            $request->merge(['table' => 'menus']);

            $validated = $request->validated();
            
            $menu = $RoleMenuservices->storeMenu($validated);

            return response()->json([
                'status' => true,
                'data' => $menu,
                'message' => 'Menu created successfully.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create Menu',
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
    public function edit(string $id, UserRoleMenuServices $RoleMenuservices)
    {
        try {
            $user_role = auth()->user()?->roles?->name;
            if ($user_role !== 'superadmin') {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized:only super admin can access.',
                ], 403);
            }

            $edit_menu = $RoleMenuservices->editMenu($id);

            return response()->json([
                'status' => true,
                'data' => $edit_menu,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Menu not found.',
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(storeMenuOrRole $request, string $id, UserRoleMenuServices $RoleMenuservices)
    {
        try {
            $user_role = auth()->user()?->roles?->name;
            if ($user_role !== 'superadmin') {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized:only super admin can access.',
                ], 403);
            }

            $request->merge(['table' => 'menus']);

            $validated = $request->validated();

            $menu = $RoleMenuservices->MenuUpdate($validated, $id);

            return response()->json([
                'status' => true,
                'data' => $menu,
                'message' => 'Menu updated successfully.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'menu not found.',
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, UserRoleMenuServices $RoleMenuservices)
    {
        try {
            $user_role = auth()->user()?->roles?->name;
            if ($user_role !== 'superadmin') {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized:only super admin can access.',
                ], 403);
            }
            $menu = $RoleMenuservices->MenuDestroy($id);
            if ($menu) {
                return response()->json([
                    'status' => true,
                    'message' => 'menu deleted successfully.',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'menu not found.',
                ], 404);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'menu not found.',
            ], 404);
        }
    }
}

<?php

namespace App\Services;

use App\Models\Roles;

use App\Models\Menu;

use App\Models\RoleMenuPermission;

class UserRoleMenuServices
{

    //store role 
    public function storeRole($data)
    {

        $role = Roles::create([
            'name' => $data['name']
        ]);
        return $role;
    }

    //get edit Role

    public function editRole($id)
    {

        $edit_user_role = Roles::findOrFail($id);

        return $edit_user_role;
    }


    //update Role
    public function roleUpdate($validated, $id)
    {
        $role = Roles::find($id);

        if (!$role) {
            return false;
        }

        $role->name = $validated['name'];
        $role->save();

        return $role;
    }

    //Role Destroy
    public function RoleDestroy($id)
    {
        $role = Roles::find($id);

        if (!$role) {
            return false;
        }
        $role->delete();
        return true;
    }
    //store Menu
    public function storeMenu($data)
    {

        $menu = Menu::create([
            'name' => $data['name']
        ]);

        return $menu;
    }
    //get edit Menu
    public function editMenu($id)
    {

        $edit_menu = Menu::findOrFail($id);


        return $edit_menu;
    }

    //update Menu
    public function MenuUpdate($validated, $id)
    {
        $menu = Menu::find($id);
        if (!$menu) {
            return false;
        }

        $menu->name = $validated['name'];
        $menu->save();

        return $menu;
    }
    //Role Destroy
    public function MenuDestroy($id)
    {
        $menu = Menu::find($id);

        if (!$menu) {
            return false;
        }
        $menu->delete();
        return true;
    }

    //RoleMenuPermission
    public function RoleMenuPermission($request){
       RoleMenuPermission::UpdateOrInsert([
            'role_id'    => $request['role_id'],
            'menu_id'    => $request['menu_id']],
            [
            'fullaccess' => $request['fullaccess'] ?? false,
            'viewonly'   => $request['viewonly'] ?? false,
            'hidden'     => $request['hidden'] ?? false,
        ]);
        return true;
    }
    //RoleMenuPermission Destroy
    public function RoleMenuPermissionDestroy($id)
    {
        $RoleMenuPermission = RoleMenuPermission::find($id);

        if (!$RoleMenuPermission) {
            return false;
        }
        $RoleMenuPermission->delete();
        return true;
    }


    //get all Roll

    public function getAllRole(){
        $Roles = Roles::all();
        return $Roles; 
    }
    //get all Menus

    public function getAllmenu()
    {
        $Menus = Menu::all();
        return $Menus;
    }
    //get all Menus

    public function getAllRoleMenuPermission()
    {
        $RoleMenuPermission = RoleMenuPermission::all();
        return $RoleMenuPermission;
    }
}

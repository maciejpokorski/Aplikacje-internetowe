<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Auth;

class PermissionController extends Controller
{
    public function index(Request $request) {
       return Permission::all();
    }

    public function store(Request $request) {
        $this->validate($request, [
            'name'=>'required|max:120|unique:roles',
        ]);
        $request->guard_name = "web";
        return Permission::create(["guard_name" => 'web', 'name' => $request->name]);
    }

    public function update(Request $request, $id) {
        $role = Permission::findOrFail($id); 
        $this->validate($request, [
            'name'=>'required|max:120|unique:roles',
        ]);
        $role->name = $request->get('name');
        $role->save();
        return $role;
    }

    public function destroy($id) {
        //Find a user with a given id and delete
        $role = Permission::findOrFail($id); 

        return $role->delete()?$id:-1;
    }
}

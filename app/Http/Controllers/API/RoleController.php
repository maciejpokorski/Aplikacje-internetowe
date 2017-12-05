<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Auth;

class RoleController extends Controller
{
    public function index(Request $request) {
       return Role::all();
    }

    public function store(Request $request) {
        $this->validate($request, [
            'name'=>'required|max:120|unique:roles',
        ]);
        $request->guard_name = "web";
        return "ok";
        return Role::create(["guard_name" => 'web', 'name' => $request->name]);
    }

    public function update(Request $request, $id) {
        $role = Role::findOrFail($id); 
        $this->validate($request, [
            'name'=>'required|max:120|unique:roles',
        ]);
        $role->name = $request->get('name');
        $role->save();
        return $role;
    }

    public function destroy($id) {
        //Find a user with a given id and delete
        $role = Role::findOrFail($id); 

        return $role->delete()?$id:-1;
    }
}

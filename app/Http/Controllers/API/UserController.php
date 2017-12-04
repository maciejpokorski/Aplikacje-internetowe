<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Laravel\Passport\Passport;

class UserController extends Controller
{
    public function index(Request $request) {
       return User::with('roles', 'permissions')->get();
    }


    public function store(Request $request) {
        $this->validate($request, [
            'name'=>'required|max:120',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6'
        ]);
        //$request->password = bcrypt($request->password);

        $user = User::create($request->only('email', 'name', 'password')); //Retrieving only the email and password data
        return $user;
    }

    public function syncRoles(Request $request, $id){
        $user = User::findOrFail($id);
        $user->syncRoles($request->all());
        return $user->roles()->get();
    }

    public function syncPermissions(Request $request, $id){
        $user = User::findOrFail($id);
        $user->syncPermissions($request->all());
        return $user->roles()->get();
    }

    public function update(Request $request, $id) {
        $user = User::findOrFail($id); //Get role specified by id

    //Validate name, email and password fields  
        $this->validate($request, [
            'email'=>'required|email|unique:users,email,'.$id,
        ]);
        $input = $request->get('email'); //Retreive the name, email and password fields
        $user->email = $input;
        $user->save();
        return $user;
    }

    public function destroy($id) {
        //Find a user with a given id and delete
        $user = User::findOrFail($id); 

        return $user->delete()?$id:-1;
    }
}

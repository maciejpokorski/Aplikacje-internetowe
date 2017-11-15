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
       return User::all();
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
}

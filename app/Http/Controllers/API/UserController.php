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
}

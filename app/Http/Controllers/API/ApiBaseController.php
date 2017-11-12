<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiBaseController extends Controller
{
   public function __construct() {
       $permissions = Auth::user()->getAllPermissions(); //herer I recieve collections
        $permissions = $permissions->pluck("name")->toArray(); //convert it to needed format

        Auth::user()->createToken('internal-user', $permissions)->accessToken;
    }
}

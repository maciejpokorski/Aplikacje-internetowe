<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Test;
use Auth;

class TestController extends Controller
{
    public function index(Request $request) {
       return \App\Test::all();
    }

    public function show($id){
        return \App\Test::find($id);
    }

}

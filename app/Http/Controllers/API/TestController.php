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

    public function store(Request $request){
        $this->validate($request, [
            'name'=>'required',
            'start_date' => 'date',
            'finish_date' => 'date|nullable'
        ]);

       $test = new \App\Test;
    
        $test->user_id = $request->user()->id;
        $test->name = $request->name;
        $test->description = $request->description; 
        $test->start_date = $request->start_date?$request->start_date:Carbon::now(); 
        $test->finish_date = $request->finish_date;
        if($test->save())
            return $test;

    }

    public function update(Request $request, $id){
        $test = \App\Test::findOrFail($id); 
        $this->validate($request, [
            'start_date' => 'date',
            'finish_date' => 'date|nullable'
        ]);
        $test->name = $request->get('name');
        $test->description = $request->get('description');
        $test->start_date = $request->get('start_date');
        $test->finish_date = $request->get('finish_date');
        
        $test->save();
        return $test;
    }

    public function destroy($id) {
        //Find a user with a given id and delete
        $test = \App\Test::findOrFail($id); 

        return $test->delete()?$id:-1;
    }
}

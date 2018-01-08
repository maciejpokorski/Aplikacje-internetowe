<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Test;
use Auth;

class TestController extends Controller
{
    public function index(Request $request) {
       return \App\Test::with('testAttempts.user')->get();
    }

    public function show($id){
        return \App\Test::find($id)->with('questions.answers')->get();
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

    public function saveAttempt(Request $request, $id){
        $attempt = \App\TestAttempt::firstOrNew(
            ['test_id' => $id, 'user_id' => $request->user()->id], ['test_id' => $id, 'user_id' => $request->user()->id, 'score' => 0, 'is_finished' => 0]
        );
        if(empty($attempt->id)){
            $attempt->save();
            return $attempt;
        }else{
            if($request->save){
                $attempt->is_finished = 1;
                $attempt->score = $this->countScore($attempt);
                $attempt->save();
            }
            return $attempt;
        }
    }

    private function countScore($attempt){
        $score = 0;
        $user_answers = \App\UserAnswer::where(['user_id' => $attempt->user_id, 'test_id' => $attempt->test_id])->with('answer')->get();
        $collection = $user_answers->each(function ($item, $key) use(&$score) {
            if($item->answer->is_correct){
                $score = $score + 1;
            }
        });
        return $score;
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

<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Question;
use Auth;

class QuestionController extends Controller
{
    public function index(Request $request) {
        $test_id = $request->test_id;
        if($test_id >= 0 && !empty($test_id)){
            $questions = \App\Question::with('tests')->get();
            $questions->each(function($question){
                    if($question->tests->contains(1)){
                        $question->checked = 1;
                    }else $question->checked = 0;
            });
            return $questions;
        }else{
            return \App\Question::with('tests')->get();
        }
    }

    public function toggleQuestionTest(Request $request){
        $question = \App\Question::find($request->question_id);
        return $question->tests()->toggle([$request->test_id]);
    }

    public function show($id){
        return \App\Question::find($id);
    }

    public function store(Request $request){
        $this->validate($request, [
            'name'=>'required'
        ]);

       $questions = new \App\Question;
    
        $questions->name = $request->name;
        $questions->description = $request->description; 

        if($questions->save())
            return $questions;

    }

    public function update(Request $request, $id){
        $questions = \App\Question::findOrFail($id); 

        $questions->name = $request->get('name');
        $questions->description = $request->get('description');
        
        $questions->save();
        return $questions;
    }

    public function destroy($id) {
        //Find a user with a given id and delete
        $questions = \App\Question::findOrFail($id); 

        return $questions->delete()?$id:-1;
    }
}

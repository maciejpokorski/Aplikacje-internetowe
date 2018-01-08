<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Answer;
use Auth;

class AnswerController extends Controller
{
    public function index(Request $request) {
       return \App\Answer::all();
    }

    public function show($id){
        return \App\Answer::find($id);
    }

    public function getAnswersForQuestion($id){
        return \App\Answer::where('question_id', $id)->get();
    }

    public function store(Request $request){
        $this->validate($request, [
            'name'=>'required',
            'is_correct' => 'required|boolean'
        ]);

       $answer = new \App\Answer;
    
        $answer->name = $request->name;
        $answer->question_id = $request->question_id;
        $answer->is_correct = $request->is_correct; 

        if($answer->save())
            return $answer;

    }

    public function update(Request $request, $id){
        $answer = \App\Answer::findOrFail($id); 

        $this->validate($request, [
            'name'=>'required',
            'is_correct' => 'required|boolean'
        ]);

        $answer->name = $request->name;
        $answer->is_correct = $request->is_correct; 
        
        $answer->save();
        return $answer;
    }

    public function destroy($id) {
        //Find a user with a given id and delete
        $answer = \App\Answer::findOrFail($id); 

        return $answer->delete()?$id:-1;
    }

    public function testAnswers(Request $request){
        if(empty($request->user_attempt_id))
            return $user_answers = \App\UserAnswer::where('test_id', $request->test_id)->where('user_id', $request->user()->id)->where('question_id', $request->question_id)->get();
         else 
            return $user_answers = \App\UserAnswer::where('test_id', $request->test_id)->where('user_id', $request->user_attempt_id)->where('question_id', $request->question_id)->get();
    }

    public function saveResult(Request $request){
        if(empty(  $user_answers = \App\UserAnswer::where('user_id', $request->user()->id)->where('test_id', $request->test_id)->where('question_id', $request->question_id)->where('answer_id', $request->answer_id)->first()))
        {
            $user_answers = new \App\UserAnswer;
            $user_answers->user_id = $request->user()->id;
            $user_answers->test_id = $request->test_id;
            $user_answers->question_id = $request->question_id;
            $user_answers->answer_id = $request->answer_id;
            $user_answers->save();
        }
        else {
            $user_answers->delete();
        }
    }
}

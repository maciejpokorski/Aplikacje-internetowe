<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestAttempt extends Model
{
    protected $fillable = [
        'user_id', 'test_id', 'score', 'is_finished'
    ];
    
    public function test(){
        return \App\Test::where('id', $this->test_id)->get();
    }

    public function user(){
        return $this->belongsTo('App\User');
    }
}

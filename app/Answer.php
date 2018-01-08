<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'name', 'is_correct'
    ];


    public function user_answer(){
        return $this->hasOne('App\UserAnswer');
    }

    public function question(){
        return $this->belongsTo('App\Question');
    }
}

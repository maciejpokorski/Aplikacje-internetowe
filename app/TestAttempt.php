<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestAttempt extends Model
{
    protected $fillable = [
        'name', 'description', 'start_date', 'finish_date'
    ];

    public function test(){
        return $this->belongsTo('App\Test');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }
}

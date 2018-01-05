<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $fillable = [
        'name', 'description', 'start_date', 'finish_date'
    ];

    public function questions(){
        return $this->belongsToMany('App\Question');
    }
}

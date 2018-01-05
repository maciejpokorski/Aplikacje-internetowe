<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'name', 'description'
    ];

    public function answers(){
        return $this->hasMany('App\Answer');
    }

    public function tests(){
        return $this->belongsToMany('App\Test');
    }
}

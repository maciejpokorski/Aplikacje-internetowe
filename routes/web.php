<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/dashboard', 'UserController@index');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/tests', function(){
    return view('tests');
});

Route::get('/tests/{id}', function($test){
    return view('single-test')->with('test', $test);
});
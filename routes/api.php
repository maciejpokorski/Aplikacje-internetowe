<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api, permissions')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/users/{id}/sync/roles', 'API\UserController@syncRoles')->middleware('auth:api, permissions');
Route::post('/users/{id}/sync/permissions', 'API\UserController@syncPermissions')->middleware('auth:api, permissions');
Route::resource('users', 'API\UserController')->middleware('auth:api, permissions');
Route::resource('permissions', 'API\PermissionController')->middleware('auth:api, permissions');
Route::resource('roles', 'API\RoleController')->middleware('auth:api, permissions');
Route::resource('tests', 'API\TestController')->middleware('auth:api, permissions');
Route::resource('questions', 'API\QuestionController')->middleware('auth:api, permissions');
Route::resource('answers', 'API\AnswerController')->middleware('auth:api, permissions');
Route::post('/questions/toggle-question-test', 'API\QuestionController@toggleQuestionTest')->middleware('auth:api, permissions');
Route::get('/answers/question/{id}', 'API\AnswerController@getAnswersForQuestion')->middleware('auth:api, permissions');
Route::get('/tests/{id}/attempt', 'API\TestController@testAttempt')->middleware('auth:api');

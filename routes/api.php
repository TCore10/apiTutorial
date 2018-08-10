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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
// Api Route for viewing details
Route::group(['middleware' => 'auth:api'], function(){
	Route::post('details', 'API\UserController@details');
});
// Api Route for Login
Route::post('login', 'API\UserController@login');
// Api Route for Registration
Route::post('register', 'API\UserController@register');
// List Articles
Route::get('articles', 'ArticleController@index');

// List Single Article
Route::get('article/{id}', 'ArticleController@show');

// Create new Article
Route::post('article', 'ArticleController@store');

// Update Article
Route::put('article', 'ArticleController@store');

// Delete Article
Route::delete('article/{id}', 'ArticleController@destroy');
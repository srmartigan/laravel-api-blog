<?php /** @noinspection PhpUndefinedClassInspection */

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/register', 'RegisterController@signup')->name('register');
Route::post('/login' , 'LoginController@login')->name('login');

//Route::apiResource('car','CarController');

Route::get('/car','CarController@index');
Route::post('/car','CarController@store');
Route::get('/car/{car}','CarController@show');
Route::put('/car/{car}','CarController@edit');
Route::delete('/car/{car}','CarController@destroy');

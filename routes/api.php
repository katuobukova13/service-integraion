<?php

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

Route::post('/amocrm/order', 'App\Http\Controllers\AmocrmOrderController@store');

Route::get('/amocrm/contacts', 'App\Http\Controllers\AmocrmContactController@index');
Route::get('/amocrm/contacts/{contact}', 'App\Http\Controllers\AmocrmContactController@show');
Route::post('/amocrm/contacts', 'App\Http\Controllers\AmocrmContactController@store');
Route::put('/amocrm/contacts/{contact}', 'App\Http\Controllers\AmocrmContactController@update');

Route::get('/amocrm/leads', 'App\Http\Controllers\AmocrmLeadController@index');
Route::get('/amocrm/leads/{lead}', 'App\Http\Controllers\AmocrmLeadController@show');
Route::post('/amocrm/leads', 'App\Http\Controllers\AmocrmLeadController@store');
Route::put('/amocrm/leads/{lead}', 'App\Http\Controllers\AmocrmLeadController@update');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});

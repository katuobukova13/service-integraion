<?php

use App\Http\Controllers\AmocrmContactController;
use App\Http\Controllers\AmocrmLeadController;
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

Route::apiResource('/amocrm/contacts', AmocrmContactController::class);
Route::apiResource('/amocrm/leads', AmocrmLeadController::class);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});

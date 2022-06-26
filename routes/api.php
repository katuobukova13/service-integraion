<?php

use App\Http\Controllers\AmocrmContactController;
use App\Http\Controllers\AmocrmLeadController;
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

Route::prefix('v1')
  ->middleware(['user_to_token', 'auth:sanctum'])
  ->group(function () {
    Route::post('/amocrm/orders', 'App\Http\Controllers\AmocrmOrderController@store')->name('orders.store');

    Route::apiResource('/amocrm/contacts', AmocrmContactController::class);
    Route::apiResource('/amocrm/leads', AmocrmLeadController::class);

    Route::post('/getcourse/user', 'App\Http\Controllers\GetcourseUserController@store')->name('user.store');
    Route::post('/getcourse/deal', 'App\Http\Controllers\GetcourseDealController@store')->name('deal.store');
  });

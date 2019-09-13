<?php

use App\BudgetRequest;
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

Route::get('budget_requests', 'BudgetRequestController@index');
Route::post('budget_requests', 'BudgetRequestController@store')->name('budget_requests.store');

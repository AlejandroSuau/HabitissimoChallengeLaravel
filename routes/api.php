<?php

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

Route::get('budget_requests/{email?}', 'BudgetRequestController@index')->name('budget_requests.index');
Route::post('budget_requests', 'BudgetRequestController@store')->name('budget_requests.store');
Route::put('budget_requests/{id}', 'BudgetRequestController@update')->name('budget_requests.update');
Route::put('budget_requests/publish/{id}', 'BudgetRequestController@publish')->name('budget_requests.publish');
Route::put('budget_requests/discard/{id}', 'BudgetRequestController@discard')->name('budget_requests.discard');

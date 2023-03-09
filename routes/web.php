<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CuttingListController;

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
    return view('voyager::login');
});

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();

    Route::group(['prefix' => 'cutting-list'], function () {
        Route::get('/', [CuttingListController::class,'getOrders']);
        Route::get('/{id}', [CuttingListController::class,'cuttingList']);
        Route::patch('/update-status/{id}', [CuttingListController::class,'updateStatus']);
    });
});

Route::group(['prefix' => 'printouts'], function () {
   Route::get('/charge-invoice', [CuttingListController::class,'getOrders']); 
   Route::get('/cutting-list', [CuttingListController::class,'getOrders']); 
   Route::get('/delivery-receipt', [CuttingListController::class,'getOrders']); 
   Route::get('/job-order', [CuttingListController::class,'getOrders']); 
   Route::get('/official-receipt', [CuttingListController::class,'getOrders']); 
});
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CuttingListController;
use App\Http\Controllers\PrintoutController;
use App\Http\Controllers\InventoryController;

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

    Route::group(['prefix' => 'inventory'], function () {
        Route::get('/', [InventoryController::class,'index']);
    });
});

Route::group(['prefix' => 'printouts'], function () {
   Route::get('/charge-invoice', [PrintoutController::class,'chargeInvoice']); 
   Route::get('/cutting-list', [PrintoutController::class,'cuttingList']); 
   Route::get('/delivery-receipt', [PrintoutController::class,'deliveryReceipt']); 
   Route::get('/job-order', [PrintoutController::class,'jobOrder']); 
   Route::get('/official-receipt', [PrintoutController::class,'officialReceipt']); 
});
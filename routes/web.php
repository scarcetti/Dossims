<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CuttingListController;
use App\Http\Controllers\PrintoutController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PredictionController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\BalancesController;

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

    Route::group(['prefix' => 'transaction'], function () {
        Route::post('create', [TransactionController::class,'storeTx']);
        Route::post('billing', [TransactionController::class,'billing']);
    });

    Route::group(['prefix' => 'cutting-list'], function () {
        Route::get('/', [CuttingListController::class,'getOrders']);
        Route::get('/{id}', [CuttingListController::class,'cuttingList']);
        Route::patch('/update-status/{transaction_id}/{job_order_id}', [CuttingListController::class,'updateStatus']);
    });

    Route::group(['prefix' => 'inventory'], function () {
        Route::get('/', [InventoryController::class,'index']);
        Route::get('/transfers', [InventoryController::class,'inboundAndTransfers']);
    });

    Route::group(['prefix' => 'balances'], function () {
        Route::get('/', [BalancesController::class,'index']);
        Route::get('/{id}', [BalancesController::class,'selected']);
        Route::post('/settle', [BalancesController::class,'settle']);
    });

    Route::group(['prefix' => 'predictions'], function () {
        Route::get('/', [PredictionController::class,'index']);
    });

    Route::group(['prefix' => 'analytics'], function () {
        Route::get('/', [AnalyticsController::class,'index']);
        Route::get('/chart/{branch_product_id}', [AnalyticsController::class,'chart']);
    });
});

Route::group(['prefix' => 'printouts'], function () {
   Route::get('/charge-invoice', [PrintoutController::class,'chargeInvoice']);
   Route::get('/cash-invoice', [PrintoutController::class,'cashInvoice']);
   Route::get('/cutting-list', [PrintoutController::class,'cuttingList']);
   Route::get('/delivery-receipt', [PrintoutController::class,'deliveryReceipt']);
   Route::get('/job-order', [PrintoutController::class,'jobOrder']);
   Route::get('/official-receipt/{txid}', [PrintoutController::class,'officialReceipt']);
});

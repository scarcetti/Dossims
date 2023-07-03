<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\BalancesController;
use App\Http\Controllers\CriticalStocksController;
use App\Http\Controllers\CuttingListController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PredictionController;
use App\Http\Controllers\PrintoutController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
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

Route::get('testingggg', function() {
    // Create a new mysqli object
    $mysqli = new mysqli('128.199.68.178', 'root', 'FlH0@8$9EdA0YY%&9Q2E!@', 'thumbzupp_wp');

    // Check the connection
    if ($mysqli->connect_errno) {
        echo 'Failed to connect to MySQL: ' . $mysqli->connect_error;
    } else {
        echo 'Connected to MySQL successfully!';
    }

    // Close the connection
    $mysqli->close();
    die();
});

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();

    Route::group(['prefix' => 'transaction'], function () {
        Route::post('create', [TransactionController::class,'storeTx']);
        Route::post('billing', [TransactionController::class,'billing']);
        Route::post('complete', [TransactionController::class,'completeTransaction']);
    });

    Route::group(['prefix' => 'cutting-list'], function () {
        Route::get('/', [CuttingListController::class,'getOrders']);
        Route::get('/{id}', [CuttingListController::class,'cuttingList']);
        Route::patch('/update-status/{transaction_id}/{job_order_id}', [CuttingListController::class,'updateStatus']);
    });

    Route::group(['prefix' => 'inventory'], function () {
        Route::get('/', [InventoryController::class,'index']);

        Route::group(['prefix' => 'transfers'], function () {
            Route::get('/', [InventoryController::class,'inboundAndTransfers']);

            Route::group(['prefix' => 'inbound'], function () {
                Route::post('create', [InventoryController::class,'createInbound']);
                Route::post('confirm', [InventoryController::class,'stockArrivalConfirm']);
            });
            Route::group(['prefix' => 'outbound'], function () {
                Route::post('create', [InventoryController::class,'createOutbound']);
            });
        });
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

    Route::group(['prefix' => 'reports'], function () {
        Route::get('/', [PrintoutController::class,'reports']);
    });

    Route::group(['prefix' => 'critical-stocks'], function () {
        Route::get('/', [CriticalStocksController::class,'index']);
    });
});

Route::group(['prefix' => 'printouts'], function () {
   Route::get('/charge-invoice/{txid}', [PrintoutController::class,'chargeInvoice']);
   Route::get('/cash-invoice/{txid}', [PrintoutController::class,'cashInvoice']);
   Route::get('/cutting-list/{txid}', [PrintoutController::class,'cuttingList']);
   Route::get('/delivery-receipt/{txid}', [PrintoutController::class,'deliveryReceipt']);
   Route::get('/job-order/{txid}', [PrintoutController::class,'jobOrder']);
   Route::get('/official-receipt/{txid}', [PrintoutController::class,'officialReceipt']);
   Route::get('/sales-report', [PrintoutController::class,'salesReport']);
//    Route::get('/test', [PrintoutController::class,'test_dl']);
});

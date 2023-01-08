<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\JobOrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;

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
    return view('superadmin.index');
});
Route::get('/superadmin', function () {
    return view('superadmin.dashboard.index');
});
Route::get('/admin', function () {
    return view('admin.dashboard.index');
});
Route::get('/frontdesk', function () {
    return view('frontdesk.dashboard.index');
});

/*
|--------------------------------------------------------------------------
| SUPERADMIN
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'superadmin'], function () {
    Route::group(['prefix' => 'employees'], function () {
        Route::post('add', [EmployeeController::class, 'addSuperadminEmployee'])->name('addSuperadminEmployee');
        Route::get('view', [EmployeeController::class, 'fetchAllEmployees'])->name('fetchAllEmployees');
    });
    Route::group(['prefix' => 'users'], function () {
        Route::post('add', [UserController::class, 'addSuperadminUser'])->name('addSuperadminUser');
        Route::get('view', [UserController::class, 'viewSuperadminUser'])->name('viewSuperadminUser');
    });
    Route::group(['prefix' => 'customers'], function () {
        Route::post('add', [CustomerController::class, 'addSuperadminCustomer'])->name('addSuperadminCustomer');
        Route::get('view', [CustomerController::class, 'viewSuperadminCustomer'])->name('viewSuperadminCustomer');
    });
    Route::group(['prefix' => 'products'], function () {
        Route::post('add', [ProductController::class, 'addSuperadminProduct'])->name('addSuperadminProduct');
        Route::get('view', [ProductController::class, 'viewSuperadminProduct'])->name('viewSuperadminProduct');
    });
    Route::group(['prefix' => 'branches'], function () {
        Route::get('add', [BranchController::class, 'addSuperadminBranch'])->name('addSuperadminBranch');
        // Route::get('view', [BranchController::class, 'viewSuperadminBranch'])->name('viewSuperadminBranch');
        Route::get('view', [BranchController::class, 'fetchAllBranches'])->name('fetchAllBranches');
    });
    Route::group(['prefix' => 'transactions'], function () {
        Route::post('completed', [TransactionController::class, 'completedSuperadminTransaction'])->name('completedSuperadminTransaction');
        Route::get('pending', [TransactionController::class, 'pendingSuperadminTransaction'])->name('pendingSuperadminTransaction');
    });
});

Route::group(['prefix' => 'admin'], function () {
    Route::group(['prefix' => 'employees'], function () {
        Route::post('add', [EmployeeController::class, 'addAdminEmployee'])->name('addAdminEmployee');
        Route::get('view', [EmployeeController::class, 'viewAdminEmployee'])->name('viewAdminEmployee');
    });
    Route::group(['prefix' => 'users'], function () {
        Route::post('add', [UserController::class, 'addAdminUser'])->name('addAdminUser');
        Route::get('view', [UserController::class, 'viewAdminUser'])->name('viewAdminUser');
    });
    Route::group(['prefix' => 'products'], function () {
        Route::post('add', [ProductController::class, 'addAdminProduct'])->name('addAdminProduct');
        Route::get('view', [ProductController::class, 'viewAdminProduct'])->name('viewAdminProduct');
    });
    Route::group(['prefix' => 'customers'], function () {
        Route::post('add', [CustomerController::class, 'addAdminCustomer'])->name('addAdminCustomer');
        Route::get('view', [CustomerController::class, 'viewAdminCustomer'])->name('viewAdminCustomer');
    });
    Route::group(['prefix' => 'transactions'], function () {
        Route::post('completed', [TransactionController::class, 'completedAdminTransaction'])->name('completedAdminTransaction');
        Route::get('pending', [TransactionController::class, 'pendingAdminTransaction'])->name('pendingAdminTransaction');
    });
});

Route::group(['prefix' => 'front-desk'], function () {
    Route::get('products', [ProductController::class, 'viewFrontdesktProduct'])->name('viewFrontdesktProduct');
    Route::get('job-orders/view', [JobOrderController::class, 'viewFrontDeskJobOrder'])->name('frontdeskjoborderview');
    /* Route::get('/frontdesk/customers/add', [CustomerController::class, 'addFrontdeskCustomer'])->name('addFrontdeskCustomer'); */
    Route::get('customers/view', [CustomerController::class, 'viewFrontdeskCustomer'])->name('viewFrontdeskCustomer');
    Route::get('transactions/completed', [TransactionController::class, 'completedFrontdeskTransaction'])->name('completedFrontdeskTransaction');
    Route::get('transactions/pending', [TransactionController::class, 'pendingFrontdeskTransaction'])->name('pendingFrontdeskTransaction');
});
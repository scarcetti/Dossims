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

/*
|--------------------------------------------------------------------------
| SUPERADMIN
|--------------------------------------------------------------------------
*/
Route::get('/superadmin/employees/add', [EmployeeController::class, 'addSuperadminEmployee'])->name('addSuperadminEmployee');
Route::get('/superadmin/employees/view', [EmployeeController::class, 'viewSuperadminEmployee'])->name('viewSuperadminEmployee');
Route::get('/superadmin/users/add', [UserController::class, 'addSuperadminUser'])->name('addSuperadminUser');
Route::get('/superadmin/users/view', [UserController::class, 'viewSuperadminUser'])->name('viewSuperadminUser');
Route::get('/superadmin/customers/add', [CustomerController::class, 'addSuperadminCustomer'])->name('addSuperadminCustomer');
Route::get('/superadmin/customers/view', [CustomerController::class, 'viewSuperadminCustomer'])->name('viewSuperadminCustomer');
Route::get('/superadmin/products/add', [ProductController::class, 'addSuperadminProduct'])->name('addSuperadminProduct');
Route::get('/superadmin/products/view', [ProductController::class, 'viewSuperadminProduct'])->name('viewSuperadminProduct');
Route::get('/superadmin/branches/add', [BranchController::class, 'addSuperadminBranch'])->name('addSuperadminBranch');
Route::get('/superadmin/branches/view', [BranchController::class, 'viewSuperadminBranch'])->name('viewSuperadminBranch');
Route::get('/superadmin/transactions/completed', [TransactionController::class, 'completedSuperadminTransaction'])->name('completedSuperadminTransaction');
Route::get('/superadmin/transactions/pending', [TransactionController::class, 'pendingSuperadminTransaction'])->name('pendingSuperadminTransaction');


Route::get('/superadmin', function () {
    return view('superadmin.dashboard.index');
});

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::get('/admin/employees/add', [EmployeeController::class, 'addAdminEmployee'])->name('addAdminEmployee');
Route::get('/admin/employees/view', [EmployeeController::class, 'viewAdminEmployee'])->name('viewAdminEmployee');
Route::get('/admin/users/add', [UserController::class, 'addAdminUser'])->name('addAdminUser');
Route::get('/admin/users/view', [UserController::class, 'viewAdminUser'])->name('viewAdminUser');
Route::get('/admin/products/add', [ProductController::class, 'addAdminProduct'])->name('addAdminProduct');
Route::get('/admin/products/view', [ProductController::class, 'viewAdminProduct'])->name('viewAdminProduct');
Route::get('/admin/customers/add', [CustomerController::class, 'addAdminCustomer'])->name('addAdminCustomer');
Route::get('/admin/customers/view', [CustomerController::class, 'viewAdminCustomer'])->name('viewAdminCustomer');
Route::get('/admin/transactions/completed', [TransactionController::class, 'completedAdminTransaction'])->name('completedAdminTransaction');
Route::get('/admin/transactions/pending', [TransactionController::class, 'pendingAdminTransaction'])->name('pendingAdminTransaction');


Route::get('/admin', function () {
    return view('admin.dashboard.index');
});

/*
|--------------------------------------------------------------------------
| FRONT DESK
|--------------------------------------------------------------------------
*/
Route::get('/front-desk/products', [ProductController::class, 'viewFrontdesktProduct'])->name('viewFrontdesktProduct');
Route::get('/front-desk/job-orders/view', [JobOrderController::class, 'viewFrontDeskJobOrder'])->name('frontdeskjoborderview');
/* Route::get('/frontdesk/customers/add', [CustomerController::class, 'addFrontdeskCustomer'])->name('addFrontdeskCustomer'); */
Route::get('/frontdesk/customers/view', [CustomerController::class, 'viewFrontdeskCustomer'])->name('viewFrontdeskCustomer');
Route::get('/front-desk/transactions/completed', [TransactionController::class, 'completedFrontdeskTransaction'])->name('completedFrontdeskTransaction');
Route::get('/front-desk/transactions/pending', [TransactionController::class, 'pendingFrontdeskTransaction'])->name('pendingFrontdeskTransaction');

Route::get('/frontdesk', function () {
    return view('frontdesk.dashboard.index');
});
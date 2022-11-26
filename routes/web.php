<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;

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
Route::get('/superadmin/customers/view', [CustomerController::class, 'viewAdminCustomer'])->name('customerview');

Route::get('/superadmin', function () {
    return view('superadmin.dashboard.index');
});
Route::get('/superadmin/employees/add', function () {
    return view('superadmin.employees.add.add');
});
Route::get('/superadmin/employees/view', function () {
    return view('superadmin.employees.view.view');
});
Route::get('/superadmin/users/add', function () {
    return view('superadmin.users.add.add');
});
Route::get('/superadmin/users/view', function () {
    return view('superadmin.users.view.view');
});
Route::get('/superadmin/customers/add', function () {
    return view('superadmin.customers.add.add');
});
Route::get('/superadmin/customers/view', function () {
    return view('superadmin.customers.view.view');
});
Route::get('/superadmin/products/add', function () {
    return view('superadmin.products.add.add');
});
Route::get('/superadmin/products/view', function () {
    return view('superadmin.products.view.view');
});
Route::get('/superadmin/branches/add', function () {
    return view('superadmin.branches.add.add');
});
Route::get('/superadmin/branches/view', function () {
    return view('superadmin.branches.view.view');
});
Route::get('/superadmin/transactions/completed', function () {
    return view('superadmin.transactions.completed.completed');
});
Route::get('/superadmin/transactions/pending', function () {
    return view('superadmin.transactions.pending.pending');
});

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::get('/admin', function () {
    return view('admin.dashboard.index');
});
Route::get('/admin/employees/add', function () {
    return view('admin.employees/add.add');
});
Route::get('/admin/employees/view', function () {
    return view('admin.employees/view.view');
});
Route::get('/admin/users/add', function () {
    return view('admin.users/add.add');
});
Route::get('/admin/users/view', function () {
    return view('admin.users/view.view');
});
Route::get('/admin/customers/add', function () {
    return view('admin.customers/add.add');
});
Route::get('/admin/customers/view', function () {
    return view('admin.customers/view.view');
});
Route::get('/admin/products/add', function () {
    return view('admin.products/add.add');
});
Route::get('/admin/products/view', function () {
    return view('admin.products/view.view');
});
Route::get('/admin/transactions/completed', function () {
    return view('admin.transactions/completed.completed');
});
Route::get('/admin/transactions/pending', function () {
    return view('admin.transactions/pending.pending');
});

/*
|--------------------------------------------------------------------------
| FRONT DESK
|--------------------------------------------------------------------------
*/
Route::get('/frontdesk', function () {
    return view('frontdesk.dashboard.index');
});
Route::get('/frontdesk/products', function () {
    return view('frontdesk.products.index');
});
Route::get('/frontdesk/job-order/view', function () {
    return view('frontdesk.joborder.view.view');
});
Route::get('/frontdesk/customers/view', function () {
    return view('frontdesk.customers.view.view');
});
Route::get('/frontdesk/transactions/completed', function () {
    return view('frontdesk.transactions.completed.completed');
});
Route::get('/frontdesk/transactions/pending', function () {
    return view('frontdesk.transactions.pending.pending');
});
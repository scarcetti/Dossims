<?php

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
    return view('superadmin.index');
});
Route::get('/superadmin', function () {
    return view('superadmin.dashboard.index');
});
Route::get('/superadmin/employees/add', function () {
    return view('superadmin.employees.add.add');
});
Route::get('/superadmin/employees/view', function () {
    return view('superadmin.employees.view.view');
});
Route::get('/superadmin/users/view', function () {
    return view('superadmin.users.view.view');
});
Route::get('/superadmin/products/add', function () {
    return view('superadmin.products.add.add');
});
Route::get('/superadmin/products/view', function () {
    return view('superadmin.products.view.view');
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

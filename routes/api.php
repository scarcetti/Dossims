<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\JobOrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('login', [AuthController::class,'login']);

Route::post('createBranch', [BranchController::class,'createBranch']);
Route::post('createBranchEmployee', [BranchController::class,'createBranchEmployee']);
Route::post('createCustomer', [CustomerController::class,'createCustomer']);
Route::post('createEmployee', [EmployeeController::class,'createEmployee']);
Route::post('createJobOrder', [JobOrderController::class,'createJobOrder']);
Route::post('createProduct', [ProductController::class,'createProduct']);
Route::post('createProductCategory', [ProductController::class,'createProductCategory']);
Route::post('createRole', [RoleController::class,'createRole']);
Route::post('createTransaction', [TransactionController::class,'createTransaction']);
Route::post('createTransactionItem', [TransactionController::class,'createTransactionItem']);
Route::post('createTransactionPayment', [TransactionController::class,'createTransactionPayment']);
Route::post('createUser', [UserController::class,'createUser']);


Route::get('fetchAllBranches', [BranchController::class,'fetchAllBranches']);
Route::get('getBranchEmployees', [BranchController::class,'getBranchEmployees']);
Route::get('fetchAllCustomers', [CustomerController::class,'fetchAllCustomers']);
Route::get('fetchAllEmployees', [EmployeeController::class,'fetchAllEmployees']);
Route::get('fetchAllJobOrders', [JobOrderController::class,'fetchAllJobOrders']);
Route::get('getProductCategories', [ProductController::class,'getProductCategories']);
Route::get('fetchAllProducts', [ProductController::class,'fetchAllProducts']);
Route::get('fetchAllRoles', [ProductController::class,'fetchAllRoles']);
Route::get('fetchAllTransactionItems', [TransactionController::class,'fetchAllTransactionItems']);
Route::get('fetchAllTransactionPayments', [TransactionController::class,'fetchAllTransactionPayments']);
Route::get('fetchAllTransactions', [TransactionController::class,'fetchAllTransactions']);
Route::get('fetchAllUsers', [UserController::class,'fetchAllUsers']);
Route::get('test', [UserController::class,'test']);


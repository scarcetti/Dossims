<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\BranchEmployeeController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\JobOrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use PHPUnit\TextUI\XmlConfiguration\Group;

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


// Route::get('fetchAllBranches', [BranchController::class,'fetchAllBranches']);
// Route::get('fetchBranchEmployees', [BranchController::class,'fetchBranchEmployees']);
// Route::get('fetchBranchEmployeeById', [BranchEmployeeController::class,'fetchBranchEmployeeById']);
// Route::get('fetchBranchEmployeeById', [BranchEmployeeController::class,'fetchBranchEmployeeById']);
// Route::get('fetchAllCustomers', [CustomerController::class,'fetchAllCustomers']);
Route::get('fetchAllEmployees', [EmployeeController::class,'fetchAllEmployees']);
Route::get('fetchAllJobOrders', [JobOrderController::class,'fetchAllJobOrders']);
Route::get('fetchAllProductCategories', [ProductController::class,'fetchAllProductCategories']);
Route::get('fetchAllProducts', [ProductController::class,'fetchAllProducts']);
Route::get('fetchAllRoles', [ProductController::class,'fetchAllRoles']);
Route::get('fetchAllTransactionItems', [TransactionController::class,'fetchAllTransactionItems']);
Route::get('fetchAllTransactionPayments', [TransactionController::class,'fetchAllTransactionPayments']);
Route::get('fetchAllTransactions', [TransactionController::class,'fetchAllTransactions']);
Route::get('fetchAllUsers', [UserController::class,'fetchAllUsers']);
Route::get('test', [UserController::class,'test']);

Route::group(['prefix' => 'branches'], function(){
    // POST API requests
    Route::post('create', [BranchController::class,'createBranch']);
    Route::post('update', [BranchController::class,'updateBranch']);
    Route::post('delete/{id}', [BranchController::class,'deleteBranch']);
    // GET API requests
    Route::get('all', [BranchController::class,'fetchAllBranches']);
    Route::get('branch/{id}', [BranchController::class,'fetchBranchById']);
    // Route::get('branch-employee/{branch_id}', [BranchEmployeeController::class,'fetchBranchEmployeeById']);
});
Route::group(['prefix' => 'branch_employees'], function(){
    // POST API requests
    Route::post('create', [BranchController::class,'createBranchEmployee']);
    Route::post('update', [BranchController::class,'updateBranchEmployee']);
    Route::post('delete/{id}', [BranchController::class,'deleteBranchEmployee']);
    // GET API requests
    Route::get('all', [BranchController::class,'fetchBranchEmployees']);
    Route::get('branch/{branch_id}', [BranchController::class,'fetchBranchEmployeeById']);
});
Route::group(['prefix' => 'customers'], function(){
    // POST API requests
    Route::post('create', [CustomerController::class,'createCustomer']);
    Route::post('update', [CustomerController::class,'updateCustomer']);
    Route::post('delete/{id}', [CustomerController::class,'deleteCustomer']);
    // GET API requests
    Route::get('all', [CustomerController::class,'fetchAllCustomers']);
});


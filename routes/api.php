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
use App\Http\Controllers\TransactionItemController;
use App\Http\Controllers\TransactionPaymentController;
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

Route::group(['prefix' => 'branches'], function(){
    // POST API requests
    Route::post('create', [BranchController::class,'createBranch'])->name('createBranch');
    Route::post('update', [BranchController::class,'updateBranch']);
    // GET API requests
    Route::get('all', [BranchController::class,'fetchAllBranches']);
    Route::get('self/{id}', [BranchController::class,'fetchBranchById']);
    // DELETE API requests
    Route::post('delete/{id}', [BranchController::class,'deleteBranch']);
});
Route::group(['prefix' => 'branch_employees'], function(){
    // POST API requests
    Route::post('create', [BranchEmployeeController::class,'createBranchEmployee']);
    Route::post('update', [BranchContrBranchEmployeeControlleroller::class,'updateBranchEmployee']);
    // GET API requests
    Route::get('all', [BranchEmployeeController::class,'fetchBranchEmployees']);
    Route::get('self/{id}', [BranchEmployeeController::class,'fetchBranchEmployeeById']);
    Route::get('branch/{branch_id}', [BranchEmployeeController::class,'fetchBranchEmployeeByBranchId']);
    // DELETE API requests
    Route::post('delete/{id}', [BranchEmployeeController::class,'deleteBranchEmployee']);
});
Route::group(['prefix' => 'customers'], function(){
    // POST API requests
    Route::post('create', [CustomerController::class,'createCustomer']);
    Route::post('update', [CustomerController::class,'updateCustomer']);
    // GET API requests
    Route::get('all', [CustomerController::class,'fetchAllCustomers']);
    Route::get('self/{id}', [CustomerController::class,'fetchCustomerById']);
    // DELETE API requests
    Route::post('delete/{id}', [CustomerController::class,'deleteCustomer']);
});
Route::group(['prefix' => 'employees'], function(){
    // POST API requests
    Route::post('create', [EmployeeController::class,'createEmployee']);
    Route::post('update', [EmployeeController::class,'updateEmployee']);
    // GET API requests
    Route::get('all', [EmployeeController::class,'fetchAllEmployees']);
    Route::get('self/{id}', [EmployeeController::class,'fetchEmployeeById']);
    // DELETE API requests
    Route::post('delete/{id}', [EmployeeController::class,'deleteEmployee']);
});
Route::group(['prefix' => 'products'], function(){
    // POST API requests
    Route::post('create', [ProductController::class,'createProduct']);
    Route::post('update', [ProductController::class,'updateProduct']);
    // GET API requests
    Route::get('all', [ProductController::class,'fetchAllProducts']);
    Route::get('self/{id}', [ProductController::class,'fetchProductById']);
    // DELETE API requests
    Route::post('delete/{id}', [ProductController::class,'deleteProduct']);
});
Route::group(['prefix' => 'product_categories'], function(){
    // POST API requests
    Route::post('create', [ProductCategoryController::class,'createProductCategory']);
    Route::post('update', [ProductCategoryController::class,'updateProductCategory']);
    // GET API requests
    Route::get('all', [ProductCategoryController::class,'fetchAllProductCategories']);
    Route::get('self/{id}', [ProductCategoryController::class,'fetchProductCategoryById']);
    // DELETE API requests
    Route::post('delete/{id}', [ProductCategoryController::class,'deleteProductCategory']);
});
Route::group(['prefix' => 'job_orders'], function(){
    // POST API requests
    Route::post('create', [JobOrderController::class,'createJobOrder']);
    Route::post('update', [JobOrderController::class,'updateJobOrder']);
    // GET API requests
    Route::get('all', [JobOrderController::class,'fetchAllJobOrders']);
    Route::get('self/{id}', [JobOrderController::class,'fetchJobOrderById']);
    // DELETE API requests
    Route::post('delete/{id}', [JobOrderController::class,'deleteJobOrder']);
});
Route::group(['prefix' => 'roles'], function(){
    // POST API requests
    Route::post('create', [RoleController::class,'createRole']);
    Route::post('update', [RoleController::class,'updateRole']);
    // GET API requests
    Route::get('all', [RoleController::class,'fetchAllRoles']);
    Route::get('self/{id}', [RoleController::class,'fetchRoleById']);
    // DELETE API requests
    Route::post('delete/{id}', [RoleController::class,'deleteRole']);
});
Route::group(['prefix' => 'transactions'], function(){
    // POST API requests
    Route::post('create', [TransactionController::class,'createTransaction']);
    Route::post('update', [TransactionController::class,'updateTransaction']);
    // GET API requests
    Route::get('all', [TransactionController::class,'fetchAllTransactions']);
    Route::get('self/{id}', [TransactionController::class,'fetchTransactionById']);
    // DELETE API requests
    Route::get('delete/{id}', [TransactionController::class,'deleteTransaction']);
});
Route::group(['prefix' => 'transaction_items'], function(){
    // POST API requests
    Route::post('create', [TransactionItemController::class,'createTransactionItem']);
    Route::post('update', [TransactionItemController::class,'updateTransactionItem']);
    // GET API requests
    Route::get('all', [TransactionItemController::class,'fetchAllTransactionItems']);
    Route::get('self/{id}', [TransactionItemController::class,'fetchTransactionItemById']);
    // DELETE API requests
    Route::post('delete', [TransactionItemController::class,'deleteTransactionItem']);
});
Route::group(['prefix' => 'transaction_payments'], function(){
    // POST API requests
    Route::post('create', [TransactionPaymentController::class,'createTransactionPayment']);
    Route::post('update', [TransactionPaymentController::class,'updateTransactionPayment']);
    // GET API requests
    Route::get('all', [TransactionPaymentController::class,'fetchAllTransactionPayments']);
    Route::get('self/{id}', [TransactionPaymentController::class,'fetchTransactionPaymentById']);
    // DELETE API requests
    Route::post('delete/{id}', [TransactionPaymentController::class,'deleteTransactionPayment']);
});
Route::group(['prefix' => 'users'], function(){
    // POST API requests
    Route::post('create', [UserController::class,'createUser']);
    Route::post('update', [UserController::class,'updateUser']);
    // GET API requests
    Route::get('all', [UserController::class,'fetchAllUsers']);
    Route::get('self/{id}', [UserController::class,'fetchUserById']);
    // DELETE API requests
    Route::post('delete/{id}', [UserController::class,'deleteUser']);
});
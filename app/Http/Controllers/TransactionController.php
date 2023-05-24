<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBillingValidation;
use App\Http\Requests\CreateQuotationValidation;
use App\Models\Transaction;
use Exception;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Events\BreadDataAdded;
use TCG\Voyager\Events\BreadDataDeleted;
use TCG\Voyager\Events\BreadDataRestored;
use TCG\Voyager\Events\BreadDataUpdated;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Http\Controllers\Traits\BreadRelationshipParser;

class TransactionController extends \TCG\Voyager\Http\Controllers\VoyagerBaseController
{
    function getBranch($column=null)
    {
        $user = Auth::user();
        $x = \App\Models\Branch::whereHas('branchEmployees.employee.user', function($q) use ($user) {
                    $q->where('id', $user->id);
                })->first();

        if(is_null($column)) {
            return is_null($x) ? false : $x;
        }
        else {
            return is_null($x) ? false : $x->$column;
        }
    }

    function current_employee($employees_list)
    {
        // dd(Auth::user());
        $current = array_filter($employees_list->toArray(), function ($employee) {
            return $employee['employee_id'] == Auth::user()->employee_id;
        });

        return json_encode($current[key($current)]);
    }

    public function index(Request $request)
    {
        // GET THE SLUG, ex. 'posts', 'pages', etc.
        $slug = $this->getSlug($request);

        // GET THE DataType based on the slug
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('browse', app($dataType->model_name));

        $getter = $dataType->server_side ? 'paginate' : 'get';

        $search = (object) ['value' => $request->get('s'), 'key' => $request->get('key'), 'filter' => $request->get('filter')];

        // $searchNames = [];
        // if ($dataType->server_side) {
        //     $searchNames = $dataType->browseRows->mapWithKeys(function ($row) {
        //         return [$row['field'] => $row->getTranslatedAttribute('display_name')];
        //     });
        // }

        $orderBy = $request->get('order_by', $dataType->order_column);
        $sortOrder = $request->get('sort_order', $dataType->order_direction);
        $usesSoftDeletes = false;
        $showSoftDeleted = false;

        // Next Get or Paginate the actual content from the MODEL that corresponds to the slug DataType
        if (strlen($dataType->model_name) != 0) {
            $model = app($dataType->model_name);

            $query = $model::select($dataType->name.'.*');

            if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope'.ucfirst($dataType->scope))) {
                $query->{$dataType->scope}();
            }

            // Use withTrashed() if model uses SoftDeletes and if toggle is selected
            if ($model && in_array(SoftDeletes::class, class_uses_recursive($model)) && Auth::user()->can('delete', app($dataType->model_name))) {
                $usesSoftDeletes = true;

                if ($request->get('showSoftDeleted')) {
                    $showSoftDeleted = true;
                    $query = $query->withTrashed();
                }
            }

            // If a column has a relationship associated with it, we do not want to show that field
            $this->removeRelationshipField($dataType, 'browse');

            if ($search->value != '' && $search->key && $search->filter) {
                $search_filter = ($search->filter == 'equals') ? '=' : 'ILIKE';
                $search_value = ($search->filter == 'equals') ? $search->value : '%'.$search->value.'%';

                $searchField = $dataType->name.'.'.$search->key;
                if ($row = $this->findSearchableRelationshipRow($dataType->rows->where('type', 'relationship'), $search->key)) {
                    // $query->whereIn(
                    //     $searchField,
                    //     $row->details->model::where($row->details->label, $search_filter, $search_value)->pluck('id')->toArray()
                    // );
                    $query->whereRelation($row->relation, $row->field, $search_filter, $search_value);
                } else {
                    if ($dataType->browseRows->pluck('field')->contains($search->key)) {
                        $query->where($searchField, $search_filter, $search_value);
                    }
                }
            }

            $row = $dataType->rows->where('field', $orderBy)->firstWhere('type', 'relationship');
            if ($orderBy && (in_array($orderBy, $dataType->fields()) || !empty($row))) {
                $querySortOrder = (!empty($sortOrder)) ? $sortOrder : 'desc';
                if (!empty($row)) {
                    $query->select([
                        $dataType->name.'.*',
                        'joined.'.$row->details->label.' as '.$orderBy,
                    ])->leftJoin(
                        $row->details->table.' as joined',
                        $dataType->name.'.'.$row->details->column,
                        'joined.'.$row->details->key
                    );
                }

                // dd($query->orderBy($orderBy, $querySortOrder));
                // dd([$orderBy, $querySortOrder]);
                // $getter,


                $dataTypeContent = call_user_func([
                    $query->orderBy($orderBy, $querySortOrder),
                    $getter,
                ]);
            } elseif ($model->timestamps) {
                $dataTypeContent = call_user_func([$query->latest($model::CREATED_AT), $getter]);
            } else {
                $dataTypeContent = call_user_func([$query->orderBy($model->getKeyName(), 'DESC'), $getter]);
            }

            // --- C U S T O M ---

            $hidden_field_filters = [];
            if(isset($request->hide_unpaid)) {
                array_push($hidden_field_filters, 'waiting for payment');
            }
            if(isset($request->hide_paid)) {
                array_push($hidden_field_filters,
                    'procuring',
                    'preparing for delivery',
                    'delivered',
                    'completed',
                );
            }

            $branch_id = $this->getBranch('id');
            $dataTypeContent = call_user_func([$query
                ->when($branch_id, function($q) use ($branch_id) {
                    return $q->where('branch_id', $branch_id);
                })
                ->whereNotIn('status', $hidden_field_filters),
            $getter]);

            // --- C U S T O M ---

            // Replace relationships' keys for labels and create READ links if a slug is provided.
            $dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType);
        } else {
            // If Model doesn't exist, get data from table name
            $dataTypeContent = call_user_func([DB::table($dataType->name), $getter]);
            $model = false;
        }

        $searchNames = [];
        if ($dataType->server_side) {
            $searchNames = $dataType->browseRows->mapWithKeys(function ($row) {
                return [$row['field'] => $row->getTranslatedAttribute('display_name')];
            });
        }

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($model);

        // Eagerload Relations
        $this->eagerLoadRelations($dataTypeContent, $dataType, 'browse', $isModelTranslatable);

        // Check if server side pagination is enabled
        $isServerSide = isset($dataType->server_side) && $dataType->server_side;

        // Check if a default search key is set
        $defaultSearchKey = $dataType->default_search_key ?? null;

        // Actions
        $actions = [];
        if (!empty($dataTypeContent->first())) {
            foreach (Voyager::actions() as $action) {
                $action = new $action($dataType, $dataTypeContent->first());

                if ($action->shouldActionDisplayOnDataType()) {
                    $actions[] = $action;
                }
            }
        }

        // Define showCheckboxColumn
        $showCheckboxColumn = false;
        if (Auth::user()->can('delete', app($dataType->model_name))) {
            $showCheckboxColumn = true;
        } else {
            foreach ($actions as $action) {
                if (method_exists($action, 'massAction')) {
                    $showCheckboxColumn = true;
                }
            }
        }

        // Define orderColumn
        $orderColumn = [];
        if ($orderBy) {
            $index = $dataType->browseRows->where('field', $orderBy)->keys()->first() + ($showCheckboxColumn ? 1 : 0);
            $orderColumn = [[$index, $sortOrder ?? 'desc']];
        }

        // Define list of columns that can be sorted server side
        $sortableColumns = $this->getSortableColumns($dataType->browseRows);

        $view = 'voyager::bread.browse';

        if (view()->exists("voyager::$slug.browse")) {
            $view = "voyager::$slug.browse";
        }

        return Voyager::view($view, compact(
            'actions',
            'dataType',
            'dataTypeContent',
            'isModelTranslatable',
            'search',
            'orderBy',
            'orderColumn',
            'sortableColumns',
            'sortOrder',
            'searchNames',
            'isServerSide',
            'defaultSearchKey',
            'usesSoftDeletes',
            'showSoftDeleted',
            'showCheckboxColumn'
        ));
    }

        public function update_order(Request $request)
        {
            $slug = $this->getSlug($request);

            $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

            // Check permission
            $this->authorize('edit', app($dataType->model_name));

            $model = app($dataType->model_name);

            $order = json_decode($request->input('order'));
            $column = $dataType->order_column;
            foreach ($order as $key => $item) {
                if ($model && in_array(SoftDeletes::class, class_uses_recursive($model))) {
                    $i = $model->withTrashed()->findOrFail($item->id);
                } else {
                    $i = $model->findOrFail($item->id);
                }
                $i->$column = ($key + 1);
                $i->save();
            }
        }

        protected function findSearchableRelationshipRow($relationshipRows, $searchKey)
        {
            $row = $relationshipRows->filter(function ($item) use ($searchKey) {
                if ($item->field != $searchKey) {
                    return false;
                }
                if ($item->details->type != 'belongsTo') {
                    return false;
                }

                return !$this->relationIsUsingAccessorAsLabel($item->details);
            })->first();

            if(!$row) return $row;

            $relation = $row->details->relation ?? \Str::camel(class_basename(app($row->details->model)));

            return (object) ['relation' => $relation, 'field' => $row->details->label];
        }

        protected function getSortableColumns($rows)
        {
            return $rows->filter(function ($item) {
                if ($item->type != 'relationship') {
                    return true;
                }
                if ($item->details->type != 'belongsTo') {
                    return false;
                }

                return !$this->relationIsUsingAccessorAsLabel($item->details);
            })
            ->pluck('field')
            ->toArray();
        }


        protected function relationIsUsingAccessorAsLabel($details)
        {
            return in_array($details->label, app($details->model)->additional_attributes ?? []);
        }

    public function show(Request $request, $id)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        $isSoftDeleted = false;

        if (strlen($dataType->model_name) != 0) {
            $model = app($dataType->model_name);
            $query = $model->query();

            // Use withTrashed() if model uses SoftDeletes and if toggle is selected
            if ($model && in_array(SoftDeletes::class, class_uses_recursive($model))) {
                $query = $query->withTrashed();
            }
            if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope'.ucfirst($dataType->scope))) {
                $query = $query->{$dataType->scope}();
            }
            $dataTypeContent = call_user_func([$query, 'findOrFail'], $id);
            if ($dataTypeContent->deleted_at) {
                $isSoftDeleted = true;
            }
        } else {
            // If Model doest exist, get data from table name
            $dataTypeContent = DB::table($dataType->name)->where('id', $id)->first();
        }

        // Replace relationships' keys for labels and create READ links if a slug is provided.
        $dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType, true);

        // If a column has a relationship associated with it, we do not want to show that field
        $this->removeRelationshipField($dataType, 'read');

        // Check permission
        $this->authorize('read', $dataTypeContent);

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($dataTypeContent);

        // Eagerload Relations
        $this->eagerLoadRelations($dataTypeContent, $dataType, 'read', $isModelTranslatable);

        $view = 'voyager::bread.read';

        if (view()->exists("voyager::$slug.read")) {
            $view = "voyager::$slug.read";
        }

        return Voyager::view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable', 'isSoftDeleted'));
    }

    public function edit(Request $request, $id)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        if (strlen($dataType->model_name) != 0) {
            $model = app($dataType->model_name);
            $query = $model->query();

            // Use withTrashed() if model uses SoftDeletes and if toggle is selected
            if ($model && in_array(SoftDeletes::class, class_uses_recursive($model))) {
                $query = $query->withTrashed();
            }
            if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope'.ucfirst($dataType->scope))) {
                $query = $query->{$dataType->scope}();
            }
            $dataTypeContent = call_user_func([$query, 'findOrFail'], $id);
        } else {
            // If Model doest exist, get data from table name
            $dataTypeContent = DB::table($dataType->name)->where('id', $id)->first();
        }

        foreach ($dataType->editRows as $key => $row) {
            $dataType->editRows[$key]['col_width'] = isset($row->details->width) ? $row->details->width : 100;
        }

        // If a column has a relationship associated with it, we do not want to show that field
        $this->removeRelationshipField($dataType, 'edit');

        // Check permission
        $this->authorize('edit', $dataTypeContent);

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($dataTypeContent);

        // Eagerload Relations
        $this->eagerLoadRelations($dataTypeContent, $dataType, 'edit', $isModelTranslatable);

        $view = 'voyager::bread.edit-add';

        if (view()->exists("voyager::$slug.edit-add")) {
            $view = "voyager::$slug.edit-add";
        }

        // fetch extra form fields
        $branches = $this->fetchBranches();
        $branch_products = $this->fetchBranchProducts();
        $payment_types = $this->fetchPaymentTypes(true);
        $payment_methods = $this->fetchPaymentMethods();
        $transaction = $this->fetchTx($id);
        $branch_employees = $this->fetchBranchEmployees();
        $logged_employee = $this->current_employee($branch_employees);

        return Voyager::view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable', 'branches', 'branch_products', 'payment_types', 'payment_methods', 'transaction', 'branch_employees', 'logged_employee'));
    }

        function fetchPaymentTypes($create=false)
        {
            return \App\Models\PaymentType::
                when($create, function($q) {
                    $q->whereIn('id', [1,2]); # Full payment & Downpayment
                })
                ->get();
        }

        function fetchPaymentMethods()
        {
            return \App\Models\PaymentMethod::whereIn('name', ['Cash', 'Check'])->get();
        }

        public function fetchTx($id)
        {
            $transaction = \App\Models\Transaction::with(
                    'transactionItems.branchProduct.product.measurementUnit',
                    'transactionItems.jobOrder',
                    'transactionItems.discount',
                    'customer',
                    'businessCustomer',
                    'cashier',
                    'employee',
                )->find($id);
            foreach ($transaction->transactionItems as $key => $value) {
                // return $value
                $transaction->transactionItems[$key]->product_name = $value->branchProduct->product->name;
                $transaction->transactionItems[$key]->price = $value->branchProduct->product->price;
            }
            return $transaction;
        }

        function allTransactionItems($transaction_id)
        {
            $transaction_items = \App\Models\TransactionItem::where('transaction_id', $transaction_id)->with('branchProduct.product.measurementUnit', 'jobOrder', 'discount', 'transaction')->get();
            foreach ($transaction_items as $key => $value) {
                $transaction_items[$key]->product_name = $value->branchProduct->product->name;
                $transaction_items[$key]->price = $value->branchProduct->product->price;
            }
            return $transaction_items;
        }

        function updateTransactionStatus($id)
        {
            return \App\Models\Transaction::where('id', $id)->update(['status' => 'procuring']);
        }

        function saveDiscounts($request)
        {
            # regex patterns
            $value_ = '/(item-)(\d*)(-discount-value)/';
            $discount_type_ = '/(item-)(\d*)(-discount-type)/';
            $per_item_ = '/(item-)(\d*)(-discount-type-per-item)/';

            foreach( $request->all() as $key => $value ) {
                if(preg_match($value_, $key)) {
                    $transaction_item_id = intval( explode('-', $key)[1] );

                    if(intval($value) > 0) {
                        $transaction_item_discounts[$transaction_item_id] = (object) [
                            'transaction_item_id' => $transaction_item_id,
                            'value' => intval($value),
                        ];
                    }
                }
                if( preg_match($discount_type_, $key) ) {
                    $transaction_item_id = intval( explode('-', $key)[1] );

                    $fixed = $value == 'fixed';
                    $percentage = $value == 'percentage';

                    if(isset($transaction_item_discounts[$transaction_item_id])) {
                        $transaction_item_discounts[$transaction_item_id]->fixed_amount = $fixed;
                        $transaction_item_discounts[$transaction_item_id]->percentage = $percentage;
                    }
                }
                if( preg_match($per_item_, $key) ) {
                    $transaction_item_id = intval( explode('-', $key)[1] );

                    if(isset($transaction_item_discounts[$transaction_item_id])) {
                        $transaction_item_discounts[$transaction_item_id]->per_item = boolval($value);
                    }
                }

            }

            if(isset($transaction_item_discounts)) {
                $transaction_item_discounts = json_decode( json_encode($transaction_item_discounts), true);

                foreach($transaction_item_discounts as $item) {
                    $transaction_item = \App\Models\Discount::
                        updateOrCreate(
                            [
                                'transaction_item_id' => $item['transaction_item_id'],
                            ],
                            [
                                'value'               => isset($item['value']) ? $item['value'] : null,
                                'per_item'            => isset($item['per_item']) ? $item['per_item'] : null,
                                'fixed_amount'        => isset($item['fixed_amount']) ? $item['fixed_amount'] : null,
                                'percentage'          => isset($item['percentage']) ? $item['percentage'] : null,
                            ]
                        );
                }
            }
        }

        function saveTransactionPayments($request)
        {

            if(intval($request->payment_type_id) == 1) {

            }
            elseif(intval($request->payment_type_id) == 2) {

            }
            elseif(intval($request->payment_type_id) == 3) {

            }
            elseif(intval($request->payment_type_id) == 4) {

            }


            // return $transaction_payment;
        }

    public function create(Request $request)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('add', app($dataType->model_name));

        $dataTypeContent = (strlen($dataType->model_name) != 0)
                            ? new $dataType->model_name()
                            : false;

        foreach ($dataType->addRows as $key => $row) {
            $dataType->addRows[$key]['col_width'] = $row->details->width ?? 100;
        }

        // If a column has a relationship associated with it, we do not want to show that field
        $this->removeRelationshipField($dataType, 'add');

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($dataTypeContent);

        // Eagerload Relations
        $this->eagerLoadRelations($dataTypeContent, $dataType, 'add', $isModelTranslatable);

        $view = 'voyager::bread.edit-add';

        if (view()->exists("voyager::$slug.edit-add")) {
            $view = "voyager::$slug.edit-add";
        }


        // fetch extra form fields
        $branches = $this->fetchBranches();
        $branch_products = $this->fetchBranchProducts();
        $payment_methods = json_encode('[]');
        $payment_types = $this->fetchPaymentTypes();

        $customers = $this->fetchCustomers();
        $business_customers = $this->fetchBusinessCustomers();
        $branch_employees = $this->fetchBranchEmployees();
        $logged_employee = $this->current_employee($branch_employees);

        return Voyager::view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable', 'branches', 'branch_products', 'payment_methods', 'payment_types', 'customers', 'business_customers', 'branch_employees', 'logged_employee'));
    }

        function fetchBranches()
        {
            return \App\Models\Branch::get();
        }

        function fetchCustomers()
        {
            return \App\Models\Customer::orderBy('first_name', 'ASC')
                        ->with('balance')
                        ->get();
        }

        function fetchBusinessCustomers()
        {
            return \App\Models\BusinessCustomer::orderBy('name', 'ASC')->get();
        }

        function fetchBranchEmployees()
        {
            $branch_id = $this->getBranch('id');
            return \App\Models\BranchEmployee::when($branch_id, function($q) use($branch_id) {
                            $q->where('branch_id', $branch_id);
                        })
                        ->with('employee')
                        ->get();
        }

        function fetchBranchProducts()
        {
            $branch_id = $this->getBranch('id');

            $branch_products = \App\Models\BranchProduct::when($branch_id, function($q) use ($branch_id) {
                    return $q->where('branch_id', $branch_id);
                })
                ->with('product.measurementUnit')
                ->get();
            foreach ($branch_products as $key => $value) {
                $branch_products[$key]->product_name = $value->product->name;
            }
            return $branch_products;
        }

        function saveProducts($request, $transaction_id)
        {
            $pattern_ = '/^(item_)(\d*)$/';

            foreach ( $request->all() as $key => $value ) {
                if( preg_match($pattern_, $key) ) {

                    $params = json_decode($value);

                    $transaction_item = \App\Models\TransactionItem::

                    updateOrCreate(
                        [
                            'transaction_id'    => $transaction_id,
                            'branch_product_id' => intval($params->branch_product_id),
                        ],
                        [
                            'price_at_purchase' => floatval($params->selection->price),
                            'quantity'          => $params->quantity,
                            'tbd'               => $params->tbd,
                            'linear_meters'     => isset($params->linear_meters) ? $params->linear_meters : null,
                        ]
                    );

                    \App\Models\JobOrder::create([
                        'transaction_item_id' => $transaction_item['id'],
                        'status'              => 'pending',
                    ]);
                }
            }
        }

        function otherTxnFields($txid)
        {
            $branch_id = $this->getBranch('id');

            if($branch_id) {
                \App\Models\Transaction::where('id', $txid)->update(['branch_id' => $branch_id]);
            }
        }

        function createTx($request)
        {
            return \App\Models\Transaction::create([
                'customer_id'               => $request->customer_id,
                'employee_id'               => $request->employee_id,
                'branch_id'                 => $this->getBranch('id'),
                'transaction_payment_id'    => null,
                'business_customer_id'      => $request->business_customer_id,
                'status'                    => 'pending',
                'transaction_placement'     => null,
            ]);
        }


    public function destroy(Request $request, $id)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // --- C U S T O M ---

        if($this->check_password_($request->password, $dataType)) {
            $msg = [
                'message'    => 'Incorrect password.',
                'alert-type' => 'error',
            ];

            return redirect()->route("voyager.{$dataType->slug}.index")->with($msg);
        }

        // --- C U S T O M ---

        // Init array of IDs
        $ids = [];
        if (empty($id)) {
            // Bulk delete, get IDs from POST
            $ids = explode(',', $request->ids);
        } else {
            // Single item delete, get ID from URL
            $ids[] = $id;
        }

        $affected = 0;

        foreach ($ids as $id) {
            $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);

            // Check permission
            $this->authorize('delete', $data);

            $model = app($dataType->model_name);
            if (!($model && in_array(SoftDeletes::class, class_uses_recursive($model)))) {
                $this->cleanup($dataType, $data);
            }

            $res = $data->delete();

            if ($res) {
                $affected++;

                event(new BreadDataDeleted($dataType, $data));
            }
        }

        $displayName = $affected > 1 ? $dataType->getTranslatedAttribute('display_name_plural') : $dataType->getTranslatedAttribute('display_name_singular');

        $data = $affected
            ? [
                'message'    => __('voyager::generic.successfully_deleted')." {$displayName}",
                'alert-type' => 'success',
            ]
            : [
                'message'    => __('voyager::generic.error_deleting')." {$displayName}",
                'alert-type' => 'error',
            ];

        return redirect()->route("voyager.{$dataType->slug}.index")->with($data);
    }

        function check_password_($password, $dataType)
        {
            $cred = [ 'id' => Auth::user()->id, 'password' => $password ];

            if(!Auth::guard('web')->attempt($cred)) {
                // return true if password is incorrect
                return true;
            }
        }


    public function storeTx(CreateQuotationValidation $request)
    {
        foreach($request->cart as $item) {
            $cart[] = [
                'branch_product_id' => $item['branch_product_id'],
                'price_at_purchase' => floatval($item['selection']['price']),
                'quantity'          => $item['quantity'],
                'tbd'               => $item['tbd'],
                'linear_meters'     => $item['linear_meters'] ?? null,
            ];
        }

        $tx = \App\Models\Transaction::create([
                'customer_id'               => $request->customer_id,
                'employee_id'               => $request->employee_id,
                'branch_id'                 => $this->getBranch('id') != 0 ? $this->getBranch('id') : null,
                'transaction_payment_id'    => null,
                'business_customer_id'      => $request->business_customer_id ?? null,
                'status'                    => 'waiting for payment',
                'transaction_placement'     => null,
            ])
            ->transactionItems()->createMany($cart);
        return $tx;
    }

    public function billing(CreateBillingValidation $request)
    {
        // SAVING DISCOUNT & UPDATING STOCKS
        foreach($request->cart as $item) {
            if(isset($item['discount_value'])) {
                $transaction_item = \App\Models\Discount::
                    updateOrCreate(
                        [
                            'transaction_item_id' => $item['id'],
                        ],
                        [
                            'value'               => $item['discount_value'],
                            'per_item'            => $item['discount_per_item'] ?? false,
                            'fixed_amount'        => $item['discount_type'] == 'fixed',
                            'percentage'          => $item['discount_type'] == 'percentage',
                        ]
                    );
            }

            $branch_product = \App\Models\BranchProduct::find($item['branch_product_id']);
            $branch_product->quantity = $branch_product->quantity - $item['quantity'];
            $branch_product->save();
        }

        $tx_payment_id = $this->savePaymentInfo($request->payment, $request->delivery_fee);


        \App\Models\Transaction::where('id', $request->txid)->update([
                'status' => 'procuring',
                'cashier_id' => $request->cashier_id,
                'transaction_payment_id' => $tx_payment_id,
                'txno' => $this->createTxno(),
            ]);

        return response(null, 200);
    }
        function savePaymentInfo($payment, $delivery_fee)
        {
            #    Payment types value:
            #       1 Downpayment
            #       2 Full payment
            #       3 Periodic payment
            #       4 Final payment

            $is_downpayment = ($payment['payment_type_id'] == 1);
            $txn_payment = \App\Models\TransactionPayment::create([
                'amount_paid' => $is_downpayment ? floatval($payment['amount_tendered']) : floatval($payment['grand_total']),
                'payment_type_id' => $payment['payment_type_id'],
                'payment_method_id' => $payment['payment_method_id'],
            ]);

            if( $is_downpayment ) {
                \App\Models\Balance::create([
                    'customer_id' => $payment['customer_id'],
                    'updated_at_payment_id' => $txn_payment->id,
                    'outstanding_balance' => floatval($payment['balance']),
                ]);
            }

            $this->delivieryFees($delivery_fee, $txn_payment->id);

            return $txn_payment->id;
        }

        function delivieryFees($request, $txid)
        {
            if( isset($request) ) {
                $fees = $request;
                \App\Models\DeliveryFees::create([
                    'transaction_payment_id' => $txid,
                    'outside_brgy'           => $fees['outside'],
                    'long'                   => $fees['long'],
                    'distance'               => intval($fees['distance']),
                    'total'                  => doubleval($fees['shippingTotal']),
                ]);
            }
        }

        function createTxno($branch_id=null)
        {
            $tx = Transaction::where('branch_id', $branch_id ?? $this->getBranch('id'))
                ->whereNotNull('txno')
                ->latest('id')
                ->first('txno');

            if( is_null($tx) ) {
                return '000001';
            }

            $x = intval($tx->txno) + 1;
            while ( strlen(strval($x)) < 6 ) {
                $x = '0' . strval($x);
            }

            return $x;
        }
}

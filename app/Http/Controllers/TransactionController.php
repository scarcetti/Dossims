<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Events\BreadDataAdded;
use TCG\Voyager\Events\BreadDataRestored;
use TCG\Voyager\Events\BreadDataUpdated;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Http\Controllers\Traits\BreadRelationshipParser;

class TransactionController extends \TCG\Voyager\Http\Controllers\VoyagerBaseController
{


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

        $searchNames = [];
        if ($dataType->server_side) {
            $searchNames = $dataType->browseRows->mapWithKeys(function ($row) {
                return [$row['field'] => $row->getTranslatedAttribute('display_name')];
            });
        }

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
                $search_filter = ($search->filter == 'equals') ? '=' : 'LIKE';
                $search_value = ($search->filter == 'equals') ? $search->value : '%'.$search->value.'%';

                $searchField = $dataType->name.'.'.$search->key;
                if ($row = $this->findSearchableRelationshipRow($dataType->rows->where('type', 'relationship'), $search->key)) {
                    $query->whereIn(
                        $searchField,
                        $row->details->model::where($row->details->label, $search_filter, $search_value)->pluck('id')->toArray()
                    );
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

                $dataTypeContent = call_user_func([
                    $query->orderBy($orderBy, $querySortOrder),
                    $getter,
                ]);
            } elseif ($model->timestamps) {
                $dataTypeContent = call_user_func([$query->latest($model::CREATED_AT), $getter]);
            } else {
                $dataTypeContent = call_user_func([$query->orderBy($model->getKeyName(), 'DESC'), $getter]);
            }

            // Replace relationships' keys for labels and create READ links if a slug is provided.
            $dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType);
        } else {
            // If Model doesn't exist, get data from table name
            $dataTypeContent = call_user_func([DB::table($dataType->name), $getter]);
            $model = false;
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
        $payment_types = $this->fetchPaymentTypes();
        $transaction_item = $this->allTransactionItems($id);

        return Voyager::view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable', 'branches', 'branch_products', 'payment_types', 'transaction_item'));
    }

    public function update(Request $request, $id)
    {
        // return $request;

        $this->saveDiscounts($request);

        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Compatibility with Model binding.
        $id = $id instanceof \Illuminate\Database\Eloquent\Model ? $id->{$id->getKeyName()} : $id;

        $model = app($dataType->model_name);
        $query = $model->query();
        if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope'.ucfirst($dataType->scope))) {
            $query = $query->{$dataType->scope}();
        }
        if ($model && in_array(SoftDeletes::class, class_uses_recursive($model))) {
            $query = $query->withTrashed();
        }

        $data = $query->findOrFail($id);

        // Check permission
        $this->authorize('edit', $data);

        // Validate fields with ajax
        $val = $this->validateBread($request->all(), $dataType->editRows, $dataType->name, $id)->validate();

        // Get fields with images to remove before updating and make a copy of $data
        $to_remove = $dataType->editRows->where('type', 'image')
            ->filter(function ($item, $key) use ($request) {
                return $request->hasFile($item->field);
            });
        $original_data = clone($data);

        $this->insertUpdateData($request, $slug, $dataType->editRows, $data);

        // update items
        // $this->saveProducts($request, $id);

        // Delete Images
        $this->deleteBreadImages($original_data, $to_remove);

        event(new BreadDataUpdated($dataType, $data));

        if (auth()->user()->can('browse', app($dataType->model_name))) {
            $redirect = redirect()->route("voyager.{$dataType->slug}.index");
        } else {
            $redirect = redirect()->back();
        }

        $this->updateTransactionStatus($id);

        return $redirect->with([
            'message'    => __('voyager::generic.successfully_updated')." {$dataType->getTranslatedAttribute('display_name_singular')}",
            'alert-type' => 'success',
        ]);
    }
        function fetchPaymentTypes()
        {
            return \App\Models\PaymentType::get();
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
            #    Payment types value:
            #       1 Downpayment
            #       2 Full payment
            #       3 Periodic payment
            #       4 Final payment

            if(intval($request->payment_type_id) == 1) {

            }
            elseif(intval($request->payment_type_id) == 2) {

            }
            elseif(intval($request->payment_type_id) == 3) {

            }
            elseif(intval($request->payment_type_id) == 4) {

            }

            $transaction_payment = \App\Models\TransactionPayment::create([
                'outstanding_balance'   => null,
                'amount_paid'           => $request->amount_tendered,
                'payment_type_id'       => intval($request->payment_type_id),
                'remarks'               => null,
            ]);

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
        $payment_types = $this->fetchPaymentTypes();

        return Voyager::view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable', 'branches', 'branch_products', 'payment_types'));
    }

        function fetchBranches()
        {
            return \App\Models\Branch::get();
        }

        function fetchBranchProducts()
        {
            $branch_products = \App\Models\BranchProduct::with('product.measurementUnit')->get();
            foreach ($branch_products as $key => $value) {
                $branch_products[$key]->product_name = $value->product->name;
            }
            return $branch_products;
        }

        // CHANCE OF ERROR WHEN SAVING SEQUENCE IS MODIFIED
        function saveProducts($request, $transaction_id)
        {
            $qty_pattern = '/(item-)(\d*)(-quantity)/';
            $price_pattern = '/(item-)(\d*)(-price)/';
            $note_pattern = '/(item-)(\d*)(-note)/';

            foreach ( $request->all() as $key => $value ) {
                if( preg_match($price_pattern, $key) ) {
                    $branch_product_id = intval( explode('-', $key)[1] );

                    $products[$branch_product_id] = (object) [ 
                        'price_at_purchase' => doubleval($value),
                        'branch_product_id' => $branch_product_id,
                        'transaction_id'    => $transaction_id,
                    ];
                }
                if( preg_match($qty_pattern, $key) ) {
                    $branch_product_id = intval( explode('-', $key)[1] );

                    $products[$branch_product_id]->quantity = intval( $value );
                }
                if( preg_match($note_pattern, $key) ) {
                    $branch_product_id = intval( explode('-', $key)[1] );

                    $products[$branch_product_id]->note = $value;
                }
            }
            $products = json_decode( json_encode($products), true);

            foreach($products as $item) {
                $transaction_item = \App\Models\TransactionItem::
                    updateOrCreate(
                        [
                            'transaction_id'    => $transaction_id,
                            'branch_product_id' => $item['branch_product_id'],
                        ],
                        [
                            'price_at_purchase' => $item['price_at_purchase'],
                            'quantity'          => $item['quantity'],
                        ]
                    );

                    \App\Models\JobOrder::create([
                        'transaction_item_id' => $transaction_item['id'],
                        'note'                => $item['note'],
                    ]);
            }
        }

    public function store(Request $request)
    {
        // return $request;
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();


        // Check permission
        $this->authorize('add', app($dataType->model_name));

        // Validate fields with ajax
        $val = $this->validateBread($request->all(), $dataType->addRows)->validate();
        $data = $this->insertUpdateData($request, $slug, $dataType->addRows, new $dataType->model_name());

        // save items
        $this->saveProducts($request, $data->id);

        event(new BreadDataAdded($dataType, $data));

        if (!$request->has('_tagging')) {
            if (auth()->user()->can('browse', $data)) {
                $redirect = redirect()->route("voyager.{$dataType->slug}.index");
            } else {
                $redirect = redirect()->back();
            }

            return $redirect->with([
                'message'    => __('voyager::generic.successfully_added_new')." {$dataType->getTranslatedAttribute('display_name_singular')}",
                'alert-type' => 'success',
            ]);
        } else {
            return response()->json(['success' => true, 'data' => $data]);
        }
    }
}

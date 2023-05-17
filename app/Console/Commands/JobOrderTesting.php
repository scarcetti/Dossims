<?php

namespace App\Console\Commands;

use App\Models\JobOrder;
use App\Models\TransactionItem;
use App\Models\BranchProduct;
use App\Models\Product;
use Illuminate\Console\Command;

class JobOrderTesting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:jo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testing for job order auto adding listener';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->test();
        return 0;
    }

    function test()
    {
        // $x = TransactionItem::addSelect([
        //     'branch_product_id' => BranchProduct::addSelect([
        //         'product_id' => Product::select('id')
        //     ])
        // ])->get();

        // $x = Product::addSelect([
        //     'products.id' => BranchProduct::addSelect([
        //         'branch_products.id' => TransactionItem::select('transaction_items.branch_product_id')
        //             ->where('transaction_items.id', 1)
        //     ])
        // ])->select('products.id')->get();

        $ready_made = Product::whereHas('branch_product.transaction_item', function($q) {
            $q->where('id', 2);
        })->first('ready_made')->ready_made;



        // $this->info($x->ready_made ?? true);
        $this->info($ready_made);
    }
}

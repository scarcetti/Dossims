<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;

class GenerateTransaction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tx:gen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Random transactions';

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
        $this->_test();
        return 0;
    }

    public function _test()
    {
        # DATE RANGE -------------------------------------------------------------------------------------

        $startDate = Carbon::now();
        $endDate = $startDate->copy()->subMonths(3);

        $dates = CarbonPeriod::create($endDate, '1 day', $startDate)->toArray();

        /*foreach ($dates as $date) {
            $this->info($date->format('Y-m-d'));
        }*/

        # DATE RANGE -------------------------------------------------------------------------------------

        # CUSTOMERS TODAY --------------------------------------------------------------------------------

        $customers_today = random_int(20, 35);

        # CUSTOMERS TODAY --------------------------------------------------------------------------------

        # GET BRANCH -------------------------------------------------------------------------------------

        $branch = $this->anticipate('What branch', ['Toril', 'Tagum']);
        $branch_id = \App\Models\Branch::where('name', 'ilike', '%'.$branch.'%')->first('id')->id;

        # GET BRANCH -------------------------------------------------------------------------------------

        while($customers_today > 0) {
            $this->info($customers_today);


            $this->handleTransactionItems($branch_id, $dates);

            $customers_today--;
        }




        $this->info('The command was successful! ');
    }

    public function handleTransactionItems($branch_id='', $dates)
    {
        $customer_purchases = random_int(2, 5);
        while($customer_purchases > 0) {

            $branch_product_id = random_int(1, \App\Models\BranchProduct::where('branch_id', $branch_id)->get()->count());

            $purchases[] = [
                'branch_product_id' => $branch_product_id,
                'price_at_purchase' => intval(\App\Models\BranchProduct::where('id', $branch_product_id)->pluck('price')[0]),
                'quantity' => random_int(4, 7),
                'tbd' => 1,
                'linear_meters' => null,
            ];
            $customer_purchases--;
        }

        $txn = \App\Models\Transaction::create([
            'customer_id' => random_int(1, \App\Models\Customer::get()->count()),
            'employee_id' => 22,
            'branch_id' => $branch_id,
            'transaction_payment_id' => $branch_id,
            'business_customer_id' => null,
            'status' => 'pending',
            'transaction_placement' => $dates[0],
        ])->transactionItems()->createMany($purchases);
        // dd($purchases);
        die($txn);
    }

    public function handlePayments($value='')
    {
        // code...
    }

}

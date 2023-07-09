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
        $endDate = $startDate->copy()->subMonths(24);

        $dates = CarbonPeriod::create($endDate, '1 day', $startDate)->toArray();

        /*foreach ($dates as $date) {
            $this->info($date->format('Y-m-d'));
        }*/

        # DATE RANGE -------------------------------------------------------------------------------------

        # CUSTOMERS TODAY --------------------------------------------------------------------------------


        # CUSTOMERS TODAY --------------------------------------------------------------------------------

        # GET BRANCH -------------------------------------------------------------------------------------

        $branch = $this->anticipate('What branch', ['Toril', 'toril', 'TORIL', 'Tagum', 'tagum', 'TAGUM']);
        $branch_id = \App\Models\Branch::where('name', 'ilike', '%'.$branch.'%')->first('id')->id;

        # GET BRANCH -------------------------------------------------------------------------------------

        $this->info('FILLING TRANSACTIONS...');

        $bar = $this->output->createProgressBar(count($dates));
        $bar->start();

        foreach($dates as $date) {

            $customers_today = random_int(20, 35);
            while($customers_today > 0) {
                // $this->info($customers_today);
                $this->handleTransactionItems($branch_id, $date);

                $customers_today--;
            }

            $bar->advance();
            // break; // F O R  T E S T I N G ! ! ! !
        }

        $bar->finish();
        $this->newLine(1);
        $this->info('TRANSACTIONS CREATED');
    }

    public function handleTransactionItems($branch_id='', $date)
    {
        $customer_purchases = random_int(2, 5);
        $subtotal = 0;

        while($customer_purchases > 0) {

            $branch_product_id = \App\Models\BranchProduct::where('branch_id', $branch_id)->get('id')->random()->id;

            $price_at_purchase = intval(\App\Models\BranchProduct::where('id', $branch_product_id)->pluck('price')[0] ?? 2023);
            $quantity = random_int(4, 7);

            $subtotal += ($price_at_purchase * $quantity);

            $purchases[] = [
                'branch_product_id' => $branch_product_id,
                'price_at_purchase' => $price_at_purchase,
                'quantity'          => $quantity,
                'tbd'               => 1,
                'linear_meters'     => 1,
            ];

            $customer_purchases--;
        }

        $txn_payment = \App\Models\TransactionPayment::create([
                'amount_paid'       => $subtotal,
                'payment_type_id'   => 2,
                'payment_method_id' => 1,
                'remarks'           => null,
            ]);

        $txn = \App\Models\Transaction::create([
                'customer_id'               => \App\Models\Customer::all('id')->random()->id,
                'employee_id'               => 22,
                'cashier_id'                => 37,
                'branch_id'                 => $branch_id,
                'transaction_payment_id'    => $txn_payment->id,
                'business_customer_id'      => null,
                'status'                    => 'completed',
                'transaction_placement'     => null,
                'created_at'                => $date,
                'updated_at'                => $date,
                'txno'                      => (new \App\Http\Controllers\TransactionController)->createTxno($branch_id),
            ])
            ->transactionItems()->createMany($purchases);

        // die($txn);
    }

        function handlePayments($value='')
        {
            #    Payment types value:
            #       1 Downpayment
            #       2 Full payment
            #       3 Periodic payment
            #       4 Final payment

            // code...
        }

}

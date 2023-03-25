@php
  function subtotal($item) {
    if($item->linear_meters) {
      return $item->linear_meters * $item->price_at_purchase;
    }
    else {
      return $item->quantity * $item->price_at_purchase; 
    }
  }
@endphp

<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<!-- {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/printouts.css') }}"> --}} -->
<style type="text/css">
    hr {
  float: right;
}
  /*.1_ {
  border-top: solid 1px;
}

.2_ {
  border-right: solid 1px;
}

.3_ {
  border-bottom: solid 1px;
}

.4_ {
  border-left: solid 1px;
}*/
td {
  border: solid 1px;
}
</style>

<div class="card" >
    <div class="card-body">
        <div class="container mb-5 mt-3">
  
            <div class="container">
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4" >
                        <center>
                            <img src="./doming_logo.jpg" alt="Doming's Steel Logo" style="width:90%">
                        </center>
                    </div>
                    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8">
                        <div class="text-center">
                            <h2 style="font-family:Bernard MT Condensed">DOMING'S STEEL TRADING</h2>
                            <small style="font-size: 14px;">Prk. 5, McArthur Highway, Brgy. Lizada, Toril District, Davao City</small><br>
                            <small style="font-size: 15px; font-weight: bold;">Franz Marion C. Quilban - Prop</small><br>
                            <small style="font-size: 13px;">VAT Reg. TIN: 416-676-701-002 • Cell No. Smart 09209680226 / Globe 09752874632</small>
                        </div>
                    </div>
                </div><br>
                <h2 class="fw-bold">CASH INVOICE</h2><br>
                <div class="row">
                    <div class="col">
                        <ul class="list-unstyled">
                            <li class="text-muted"><i class="fas fa-circle" style="color:#84B0CA ;"></i> 
                                <span class="fw-bold">Customer: </span>
                                {{ $transaction->customer->first_name }} {{ $transaction->customer->last_name }}
                            </li>
                            <li class="text-muted"><i class="fas fa-circle" style="color:#84B0CA ;"></i>
                                <span class="fw-bold">Address: </span>
                                Km 10. Location Toril, Davao City
                            </li>
                            <li class="text-muted"><i class="fas fa-circle" style="color:#84B0CA ;"></i>
                                <span class="fw-bold">Contact: </span>
                                09123456789
                            </li>
                        </ul>
                    </div>
                <div class="col">
                    <ul class="list-unstyled">
                        <li class="text-muted"><i class="fas fa-circle" style="color:#84B0CA ;"></i> <span
                            class="fw-bold">Date: </span>March 14, 2023</li>
                    </ul>
                </div>
            </div>
    
            <div class="row my-2 mx-1 justify-content-center">
                <table class="table table-striped table-borderless">
                    <thead style="background-color:#84B0CA ;" class="text-white">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Product</th>
                            <th scope="col">Qty</th>
                            <th scope="col">Unit Price</th>
                            <th scope="col">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaction->transactionItems as $item)
                            <tr>
                                <th class="border border-white">1</th>
                                <td class="border border-white">{{ $item->branchProduct->product->name }}</td>
                                <td class="border border-white">{{ $item->quantity }}</td>
                                <td class="border border-white">{{ $item->price_at_purchase }}</td>
                                <td class="border border-white">{{ subtotal($item) }}</td>
                            </tr>
                        @endforeach 
                       
                        <tr>
                            <th class="border border-white"></th>
                            <td class="border border-white"></td>
                            <td class="border border-white"></td>
                            <td class="border border-white">SubTotal</td>
                            <td class="border border-white">P3588</td>
                        </tr>
                        <tr>
                            <th class="border border-white"></th>
                            <td class="border border-white"></td>
                            <td class="border border-white"></td>
                            <td class="border border-white">Tax(15%)</td>
                            <td class="border border-white">P538.2</td>
                        </tr>
                        <tr>
                            <th class="border border-white"></th>
                            <td class="border border-white"></td>
                            <td class="border border-white"></td>
                            <td class="border border-white fw-bold">TOTAL AMOUNT</td>
                            <td class="border border-white fw-bold">P4126.2</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    <div class="container">
        <table>
            <tbody>
                <td style="padding: 10px; font-size: 12px;">
                    <p>It is understood that DOMING's STEEL TRADING assumes no liability for any damages as a result of service(s) rebdered. Items not claimed withing 30 days from due date shall be disposed of. 
                        <span class="fw-bold">FULL PAYMENT UPON ORDER</span>
                    </p><br>
                    
                       <center>
                            <h5 style="font-size: 12px;">CUSTOMER'S NAME & SIGNATURE &emsp;
                                <span> PREPARED BY &emsp;</span>
                                <span> RELEASED BY DATE &emsp;</span>
                                <span> RECEIVED BY DATE &emsp;</span>
                            </h5>
                       </center>
                        
                    </span>
                </td>
            </tbody>
        </table><br>
        <p>By:
            <u>Alan Benedict Golpeo</u><br>
            <i>Cashier/Authorized Representative</i>
        </p><br>
    </div>
</div>
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
                    <div class="col-xl-3" >
                        {{-- <img src="./doming_logo.jpg" alt="Doming's Steel Logo" style="width:250px;"> --}}
                        <img src = "{{ asset('/images/domings.png') }}" alt="Doming's Steel Logo" width="250px" />
                    </div>
                    <div class="col-xl-6">
                        <div class="text-center">
                            <h2 style="font-family:Bernard MT Condensed">DOMING'S STEEL TRADING</h2>
                            <small style="font-size: 14px;">Prk. 5, McArthur Highway, Brgy. Lizada, Toril District, Davao City</small><br>
                            <small style="font-size: 15px; font-weight: bold;">Franz Marion C. Quilban - Prop</small><br>
                            <small style="font-size: 13px;">VAT Reg. TIN: 416-676-701-002 • Cell No. Smart 09209680226 / Globe 09752874632</small>
                        </div>
                    </div>
                </div><br><br>
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
                <td style="padding: 10px; font-size: 12px;"><p><span class="fw-bold">PROMISSORY NOTE:</span> The undersigned buyer agree to pay the above account within 30 days after date. Failure to pay the above account after stated period of time the buyer shall be oblidged to pay a surcharge of 20 %. All civil action on this contract shall be institured in the count of Davao City and the buyer agree to pay an attorney's fee and court cost should the seller instituted legal action.</p></td>
            </tbody>
        </table>
        <div class="row">
            <p style="text-align: right; font-size: 12px;">Received the goods above in good order and conditions.</p><br>
            <div class="col-xl-3" >
                <hr style="height:3px;background-color:#000000; width: 25% "><br>
            </div>
            <div class="col-xl-9" >
                <hr style="height:3px;background-color:#000000; width: 25% "><br>
                
            </div>
        </div>
        <div class="row">
            <div class="col" >
                <h5 style="text-align: left; font-size: 12px;">&emsp;Cashier/ Authorized Representative</h5>
            </div>
            <div class="col" >
                <h5 style="text-align: right; font-size: 12px;">Customer's Signature &emsp;&emsp;</h5>
            </div>
        </div>
        
    </div>
</div>

<!--
<table>
  <thead>
     <tr>
      <th>Product</th>
      <th>Quantity</th>
      <th>Unit price</th>
      <th>Subtotal</th>
    </tr>
  </thead>
  <tbody>
    <tr>
        <td class="1_ 2_"></td>
        <td class="1_ 2_"></td>
        <td class="1_ 2_"></td>
        <td class="1_ 2_"></td>
      </tr>
 

 {{--   @foreach($transaction->transactionItems as $item) --}}
      <tr>
        <td class="1_ 2_">{{-- {{ $item->branchProduct->product->name }} --}}</td>
        <td class="1_ 2_">{{-- {{ $item->quantity }} --}}</td>
        <td class="1_ 2_">{{-- {{ $item->price_at_purchase }} --}}</td>
        <td class="1_ 2_">{{-- {{ subtotal($item) }} --}}</td>
      </tr>
   {{--  @endforeach  --}}
  </tbody>
   Customer name:{{-- {{ $transaction->customer->first_name }} {{ $transaction->customer->last_name }} --}}  Elon Moist 
</table>
-->
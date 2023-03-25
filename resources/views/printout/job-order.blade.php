<!-- @php
  function subtotal($item) {
    if($item->linear_meters) {
      return $item->linear_meters * $item->price_at_purchase;
    }
    else {
      return $item->quantity * $item->price_at_purchase; 
    }
  }
@endphp -->

<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<!-- {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/printouts.css') }}"> --}} -->
<style type="text/css">
    hr {
  float: right;
}
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
                <h2 class="fw-bold">JOB ORDER</h2><br>
                <div class="row">
                    <div class="col">
                        <ul class="list-unstyled">
                            <li class="text-muted"><i class="fas fa-circle" style="color:#84B0CA ;"></i> 
                                <span class="fw-bold">Customer: </span>
                                Elon Moist
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
                            <th scope="col">QTY</th>
                            <th scope="col">DESCRIPTION</th>
                            <th scope="col">U.P.</th>
                            <th scope="col">AMOUNT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th class="border border-white">000001</th>
                            <th class="border border-white">15</th>
                            <td class="border border-white">0.4mmX1.2mmX1m</td>
                            <td class="border border-white">218</td>
                            <td class="border border-white">P25545</td>
                        </tr>
                        <tr>
                            <th class="border border-white">000002</th>
                            <th class="border border-white">5</th>
                            <td class="border border-white">0.4mmX1.2mmX1m</td>
                            <td class="border border-white">218</td>
                            <td class="border border-white">P74512</td>
                        </tr>
                        <tr>
                            <th class="border border-white" colspan="3"></th>
                            <th class="border border-white" style="text-align: left;">TOTAL</th>
                            <td class="border border-white">P100057</td>
                        </tr>
                        <tr>
                            <th class="border border-white" colspan="3"></th>
                            <th class="border border-white" style="text-align: left;">AMOUNT</th>
                            <td class="border border-white">P215021</td>
                        </tr>
                        <tr>
                            <th class="border border-white" colspan="3"></th>
                            <th class="border border-white" style="text-align: left;">ADVANCE</th>
                            <td class="border border-white">P25784</td>
                        </tr>
                        <tr>
                            <th class="border border-white" colspan="3"></th>
                            <th class="border border-white" style="text-align: left;">BALANCE</th>
                            <td class="border border-white">P47851</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    <div class="container">
        <p style="font-size: 12px;"><span class="fw-bold" >TERMS:</span> It is understood that Doming's Steel Trading assumes no liability for any damages to items after receipt and delivery. Items not claimed with in 30 days from due date shall be disposed of.</p>
        <div class="row">
            <div class="col-xl-3" >
                <!-- <hr style="height:3px;background-color:#000000; width: 25% "><br> -->
            </div>
            <div class="col-xl-9" >
                <hr style="height:3px;background-color:#000000; width: 25% "><br>
                
            </div>
        </div>
        <div class="row">
            <div class="col" >
                <h5 style="text-align: left; font-size: 12px;">ISSUED BY: ___________________________</h5>
            </div>
            <div class="col" >
                <h5 style="text-align: right; font-size: 12px;">Customer's Signature &emsp;&emsp;</h5>
            </div>
            <h3 style="color: red;">00008051</h3>
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
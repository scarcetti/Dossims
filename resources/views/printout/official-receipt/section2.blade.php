@php
    function toWord($x)
    {
        $f = new \NumberFormatter('en', NumberFormatter::SPELLOUT);
        return strtoupper( $f->format($x) );
    }
@endphp
<div class="row" style="">
    <span>
        <img alt="asd" src="{{ public_path('images/domings.png') }}" style="width: 200px; margin-top: -20px">
    </span>

    <div style="position: absolute; right: 0; top: 0;">
        <div style="font-size: 27px; font-weight: bold;">
            DOMING'S STEEL TRADING
        </div>
        <div>
            Prk. 5, McArthur Highway. Brgy Lizada, Toril, Davao City
        </div>
        <div>
            Franz Marlon C. Quilban - Prop *VAT Reg. TIN: 416-676-701-002
        </div>
        <div>
            Cell no. Smart 09209680226 / Globe 09752874632
        </div>
    </div>
</div>
<br><br>
<div class="row" style=" position: relative;">
    <div style="font-size: 25px; font-weight: bold;">
        OFFICIAL RECEIPT
    </div>
    <div style="position: absolute; right: 0; top: 5px;">
        <table>
            <tbody>
                <tr>
                    <td class="nob">Date</td>
                    <td class="ob" width="80%" style="padding-left: 20px;">{{ \Carbon\Carbon::parse($transaction->payment->created_at)->format('M d, Y') }}</td>
                </tr>
            </tbody>
            {{-- Date ____________ --}}
        </table>
    </div>
</div>
<div style=" position: relative; font-size:18px">
    <table width="100%">
        <tbody>
            <tr>
                <td class="nob" style="width: 15%">Received from</td>
                <td class="ob">
                    {{ $transaction->customer->first_name }} {{ $transaction->customer->last_name }}
                    {{-- {{ $transaction->cashier->first_name }} {{ $transaction->cashier->last_name }} --}}
                </td>
            </tr>
            <tr>
                <td class="nob">with TIN</td>
                <td class="ob"></td>
            </tr>
            <tr>
                <td class="nob">and address at</td>
                <td class="ob">{{ $transaction->customer->address }}</td>
            </tr>
            <tr>
                <td class="nob">business style of</td>
                <td class="ob"></td>
            </tr>
            <tr>
                <td class="nob">the sum of</td>
                <td class="nob" class="nob" style="font-weight: bold;">
                    {{ toWord($transaction->payment->amount_paid) }}
                </td>
            </tr>
            <tr>
                <td class="nob"></td>
                <td class="ob"></td>
            </tr>
            <tr>
                <td class="nob">PESOS</td>
                <td class="ob">
                    {{ '₱' . number_format($transaction->payment->amount_paid, 2) }}
                </td>
            </tr>
            <tr>
                <td class="nob">In payment for</td>
                <td class="ob"></td>
            </tr>
        </tbody>
    </table>
</div>
<div style=" position: relative; margin-top: 20px;">
    <div style="float: left; width: 50%;">
        <table width="90%">
            <tbody>
                <tr>
                    <td colSpan="2">Sr. Citizen TIN</td>
                </tr>
                <tr>
                    <td class="nb" width="50%">OSCA/PWS ID No.</td>
                    <td class="nb" width="50%">Signature</td>
                </tr>
                <tr>
                    <td class="nt"></td>
                    <td class="nt"></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div style="float: right; width: 50%; margin-top: 40px">
        <table width="100%" style="margin-bottom: 5px;">
            <thead>
                <tr>
                    <th class="nob" width="5%">By:</th>
                    <th class="ob">{{ $transaction->cashier->first_name }} {{ $transaction->cashier->last_name }}</th>
                </tr>
            </thead>
        </table>
        <center>
            Cashier / Authorized Representative
        </center>
    </div>
</div>
</div>

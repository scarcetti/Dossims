{{-- @php
    function toWord($x)
    {
        $f = new \NumberFormatter('en', NumberFormatter::SPELLOUT);
        return strtoupper( $f->format($x) );
    }
@endphp --}}
<div class="row" style="">
    <span>
        <img alt="asd" src="{{ public_path('images/domings.png') }}" style="width: 350px; margin-top: -20px">
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
<br>
<div class="row" style=" position: relative;">
    <div style="font-size: 25px; font-weight: bold;">
        CHARGE INVOICE
    </div>
    <div style="position: absolute; right: 0; top: 5px;">
        <table>
            <tbody>
                <tr>
                    <td class="nob">Date</td>
                    <td class="ob" width="80%" style="padding-left: 20px;">{{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y') }}</td>
                </tr>
            </tbody>
            {{-- Date ____________ --}}
        </table>
    </div>
</div>

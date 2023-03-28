@php
    $invoice_fields = ['Total Sales', 'Less VAT', 'Total', 'Less: SC/PWD Disc', 'Total Due', 'Less: Withhodling Tax', 'Amount Due', '', 'VATable Sales', 'VAT-Exempt Sales', 'Zero Rated Sales', 'Vat Amount', 'Total Sales'];
@endphp
<table width="100%">
    <thead>
        <tr>
            <th colspan="2">
                In settlement of the following
            </th>
        </tr>
        <tr>
            <th width="50%">
                Billing invoice No.
            </th>
            <th width="50%">
                Amount
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($invoice_fields as $item)
            <tr>
                <td>{{ $item }}</td>
                <td></td>
            </tr>
        @endforeach
        <table width="100%">
            <thead>
                <tr>
                    <th colspan="2">FORM OF PAYMENT</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td width="50%">
                        <label>Cash</label>
                        <input type="checkbox" name="" {{ $transaction->payment->payment_method_id == 1 ? 'checked' : '' }}>
                    </td>
                    <td width="50%">
                        <label>Check</label>
                        <input type="checkbox" name="" {{ $transaction->payment->payment_method_id != 1 ? 'checked' : '' }}>
                    </td>
                </tr>
            </tbody>
        </table>
    </tbody>
</table>


{{-- <div style=" position: relative; font-size:18px;">
    <table width="100%" >
        <tbody>
            <tr>
                <td colspan="3">
                    <p align="justify" style="padding: 10px;">
                        <center>
                            It is understood that DOMING's STEEL TRADING assumes no luiability for any damages as a result of service(s) ordered. Items not claimed within 30 days from due date
                        shall be disposed of.<br>
                        <b>FULL PAYMENT UPON ORDER</b>
                        </center>
                    </p>
                    <table width="100%" >
                        <tbody>
                            <tr>
                                <td class="ob" align="center">{{ $transaction->cashier->first_name }} {{ $transaction->cashier->last_name }}</td>
                                <td class="ob" align="center"></td>
                                <td class="ob" align="center"></td>
                                <td class="ob" align="center">{{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <td class="nob" align="center">CASHIER'S NAME & SIGNATURE</td>
                                <td class="nob" align="center">PREPARED BY</td>
                                <td class="nob" align="center">RELEASED BY DATE</td>
                                <td class="nob" align="center">RECEIVED BY DATE</td>
                            </tr>
                        </tbody>
                    </table>
                </td>

            </tr>

        </tbody>
    </table>
</div>
 --}}

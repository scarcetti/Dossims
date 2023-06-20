<div style=" position: relative; font-size:18px; top: 110px;">
    <table width="100%" >
        <tbody>
            <tr>
                <td colspan="3">
                    <p align="justify" style="padding: 10px;">
                        <b></b>
                        Sales report for the month of {{ $month }} , {{ $year }} for {{ $branch_name }} branch:
                        Total Gross Sales: ₱&nbsp;<b>{{ number_format($sum, 2, '.', ',') }}</b>
                    </p>
                </td>
            </tr>
        </tbody>
    </table>
</div>

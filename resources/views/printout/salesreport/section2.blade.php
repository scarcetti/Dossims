<div style=" position: relative; font-size:18px; top: 110px;">
    <table width="100%" >
        <tbody>
            <tr>
                <td colspan="3">
                    <p align="justify" style="padding: 10px;">
                        <b></b>
                        @if($start == $end)
                            Sales report for the month of {{ \Carbon\Carbon::parse($start)->format('F d, Y') }} for {{ $branch_name }} branch:
                        @else
                            Sales report from {{ \Carbon\Carbon::parse($start)->format('F d, Y') }} until {{ \Carbon\Carbon::parse($end)->format('F d, Y') }} for {{ $branch_name }} branch:
                        @endif
                        Total Gross Sales: ₱&nbsp;<b>{{ number_format($sum, 2, '.', ',') }}</b>
                    </p>
                </td>
            </tr>
        </tbody>
    </table>
</div>

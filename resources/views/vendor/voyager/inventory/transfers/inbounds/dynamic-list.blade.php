{{-- <div
    v-for="(item, index) in inboundStocks"
    :key="index"
    style="display: flex;"
>
    <div class="col-md-6">
        @{{ item.product.name }}
    </div>
    <div class="col-md-6">
        @{{ item.product.id }}
    </div>
</div>
 --}}

<table class="inbound-stocks">
    <thead>
        <tr>
            <th>Product</th>
            <th>Stock</th>
            <th colspan="2"></th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(item, index) in inboundStocks" :key="index">
            <td>@{{ item.product.name }}</td>
            <td>@{{ item.quantity }}&nbsp;@{{ item.product.measurement_unit.name }}</td>

            <td v-if="item.measurement_unit_id == 2" style="width: 15%;">
                {{-- <label class="control-label">asdasd</label> --}}
                <small>Pieces</small>
                <input type="number" min="0"/>
            </td>
            <td v-if="item.measurement_unit_id == 2" style="width: 15%;">
                <small>Meters</small>
                <input type="number" min="0"/>
            </td>

            <td v-if="item.measurement_unit_id != 2" style="width: 15%;">
                <small>@{{ item.product.measurement_unit.name }}</small>
                <input type="number" min="0" :max="item.quantity"/>
            </td>
            <td v-if="item.measurement_unit_id != 2" style="width: 15%;"></td>
        </tr>
    </tbody>
</table>

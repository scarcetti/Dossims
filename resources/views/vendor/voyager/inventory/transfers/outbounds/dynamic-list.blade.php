<table class="outbound-stocks">
    <thead>
        <tr>
            <th>Product</th>
            <th>Stock</th>
            <th colspan="2"></th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(item, index) in outboundStocks" :key="index" :class="`qty_validate_${item.id}`">
            <td>@{{ item.product.name }}</td>
            <td>@{{ item.quantity }}&nbsp;@{{ item.product.measurement_unit.name }}</td>

            <td v-if="item.measurement_unit_id == 2" style="width: 15%;">
                <small>Pieces</small>
                <input @input="stock_validate('outbound-stocks', item.id)" type="number" min="0" :max="item.quantity" v-model="outboundStocks[index].pcs" :product_name="item.product.name" unit="pcs" />
            </td>
            <td v-if="item.measurement_unit_id == 2" style="width: 15%;">
                <small>Meters</small>
                <input @input="stock_validate('outbound-stocks', item.id)" type="number" min="0" :max="item.quantity" v-model="outboundStocks[index].meters" :product_name="item.product.name" unit="meters" />
            </td>

            <td v-if="item.measurement_unit_id != 2" style="width: 15%;">
                <small>@{{ item.product.measurement_unit.name }}</small>
                <input @input="stock_validate('outbound-stocks', item.id)" type="number" min="0" :max="item.quantity" v-model="outboundStocks[index].pcs" :product_name="item.product.name" unit="pcs" />
            </td>
            <td v-if="item.measurement_unit_id != 2" style="width: 15%;"></td>
        </tr>
    </tbody>
</table>

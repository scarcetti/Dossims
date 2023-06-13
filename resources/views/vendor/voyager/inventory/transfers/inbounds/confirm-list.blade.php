<table class="inbound-stocks">
    <thead>
        <tr>
            <th>Product</th>
            <th>Pieces</th>
            <th>Meters</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(item, index) in confirmInboundList" :key="index" :class="`qty_validate_${item.id}`">
            <td>@{{ item.name }}</td>

            <td v-if="item.meters" style="width: 15%;">
                <label>@{{ item.pcs }}</label>
            </td>
            <td v-if="item.meters" style="width: 15%;">
                <label>@{{ item.meters }}</label>
            </td>

            <td v-if="item.meters == null" style="width: 15%;">
                <label>@{{ item.pcs }}</label>
            </td>
            <td v-if="item.meters == null" style="width: 15%;"></td>
        </tr>
    </tbody>
</table>

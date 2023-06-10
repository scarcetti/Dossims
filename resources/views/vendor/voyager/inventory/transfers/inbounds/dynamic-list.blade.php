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
            <th>Qty</th>
            <th>Meters</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(item, index) in inboundStocks" :key="index">
            <td>@{{ item.product.name }}</td>
            <td><input type="number" placeholder="..."/></td>
            <td><input type="number" placeholder="..."/></td>
        </tr>
    </tbody>
</table>

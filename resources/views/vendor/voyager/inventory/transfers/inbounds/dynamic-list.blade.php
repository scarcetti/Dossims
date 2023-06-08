<div
    v-for="(item, index) in inboundStocks"
    :key="index"
>
    @{{ item.product.name }}
</div>

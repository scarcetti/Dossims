<input :name="`item-${item.id}-price`" :value="item.price_at_purchase" hidden />
<div :class="`cartContainer item-${item.id}`">
    <div>
        <div>
            <small>Product name: </small>
            <h4 style="margin: 0 0 10px 0">@{{ item.product_name }}</h4>
        </div>
        <div>
            <small>Item price: </small>
            <h4 style="margin: 0">₱ @{{ item.price }}</h4>
        </div>
    </div>
    <div>
        <div>
            <small>@{{ item.product.measurement_unit.name }}: </small>
            <input
                class="form-control"
                value="1"
                type="number"
                :name="`item-${item.id}-quantity`"
                min="0"
                :max="item.quantity"
                style="margin: 0 0 6px 0"
                v-on:change="valueChanged(`item-${item.id}`, item.price, index)"
                v-on:input="valueChanged(`item-${item.id}`, item.price, index)"
            >
        </div>
        <div>
            <small>Subtotal: </small>
            <h4 class="subtotal" style="margin: 0">₱ @{{ item.price }}</h4>
        </div>
    </div>
    <div style="align-self: self-start; margin-top: 7px;">
        <small>TBD field: </small>
        <input
            class="form-control"
            value="1"
            type="number"
            :name="`item-${item.id}-tbd`"
            step="0.01"
            min="0.01"
            style="margin: 0 0 6px 0"
            v-on:change="valueChanged(`item-${item.id}`, item.price, index)"
            v-on:input="valueChanged(`item-${item.id}`, item.price, index)"
        >
    </div>
    <div {{-- style="margin-left: auto;" --}}>
        <h5>Stock: </h5>
        <h4> @{{ item.quantity }} @{{ item.product.measurement_unit.name }}</h4>
    </div>
</div>
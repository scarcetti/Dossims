<input v-if="value[index].selection" :name="`item-${value[index].selection.id}-price`" :value="value[index].selection.price" hidden />
<div class="cartContainer">
{{-- <div :class="`cartContainer item-${item.id}`"> --}}
    <div>
        <div>
            <small>Product name: </small>
            {{-- <h4 style="margin: 0 0 10px 0">@{{ value[index].selection.product_name }}</h4> --}}
            <multiselect
                v-model="value[index].selection"
                @input="cartItemSelect(item)"
                deselect-label="Can't remove this value"
                track-by="product_name"
                label="product_name"
                placeholder="Product name"
                :options="branchProducts"
                :searchable="true"
                :allow-empty="false"
                style="min-width: 20vw;"
            />
        </div>
        <div v-if="value[index].selection">
            <small>Item price: </small>
            <h4 style="margin: 0">₱ @{{ value[index].selection.price }}</h4>
        </div>
    </div>
    <div v-if="value[index].selection">
        <div>
            <small>@{{ value[index].selection.product.measurement_unit.name }}: </small>
            <input
                class="form-control"
                value="1"
                type="number"
                :name="`item-${value[index].selection.id}-quantity`"
                min="0"
                :max="value[index].selection.quantity"
                style="margin: 0 0 6px 0"
                v-on:change="valueChanged(`item-${value[index].selection.id}`, value[index].selection.price, index)"
                v-on:input="valueChanged(`item-${value[index].selection.id}`, value[index].selection.price, index)"
            >
        </div>
        <div>
            <small>Subtotal: </small>
            <h4 :class="`subtotal item-${value[index].selection.id}`" style="margin: 0">₱ @{{ value[index].selection.price }}</h4>
        </div>
    </div>
    <div v-if="value[index].selection" style="align-self: self-start; margin-top: 7px;">
        <small>TBD field: </small>
        <input
            class="form-control"
            value="1"
            type="number"
            :name="`item-${value[index].selection.id}-tbd`"
            step="0.01"
            min="0.01"
            style="margin: 0 0 6px 0"
            v-on:change="valueChanged(`item-${value[index].selection.id}`, value[index].selection.price, index)"
            v-on:input="valueChanged(`item-${value[index].selection.id}`, value[index].selection.price, index)"
        >
    </div>
    <div v-if="value[index].selection">
        <h5>Stock: </h5>
        <h4> @{{ value[index].selection.quantity }} @{{ value[index].selection.product.measurement_unit.name }}</h4>
    </div>
</div>
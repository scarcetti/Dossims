<input v-if="value[index].selection" :name="`item-${value[index].selection.id}-price`" :value="value[index].selection.price" hidden />
<div :class="`cartContainer item-${index}`">
    <input v-if="value[index].selection" :name="`item_${index}`" :value="JSON.stringify(value[index])" hidden>
    <div class="col-md-1" v-if="value[index].selection">
        <span v-if="value.length > 1" @click="deleteCartItem(index)">
            <i class="voyager-x" style="font-size: 25px;"></i>
        </span>
    </div>
    <div class="col-md-7">
        <div>
            <small>Product name: </small>
            <multiselect
                v-model="value[index].selection"
                @input="cartItemSelect(`item-${value[index].selection.id}`, value[index].selection.price, index, value[index].selection.id)"
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
    <div class="col-md-2" v-if="value[index].selection">
        <div>
            <small>
            	@{{ value[index].selection.product.measurement_unit.name == 'Linear Meter' ? 
            			'Quantity' :
            			value[index].selection.product.measurement_unit.name
	            }}: 
            </small>
            <input
                v-model="value[index].quantity"
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
    <div class="col-md-2" v-if="value[index].selection" {{-- style="align-self: self-start; margin-top: 7px;" --}}>
    	<div v-if="value[index].selection.product.measurement_unit.name == 'Linear Meter'">
	        <small>Linear meters: </small>
	        <input
                v-model="value[index].linear_meters"
	            class="form-control"
	            value="1"
	            {{-- type="number" --}}
	            :name="`item-${value[index].selection.id}-linear-meters`"
	            style="margin: 0 0 6px 0"
	            v-on:change="valueChanged(`item-${value[index].selection.id}`, value[index].selection.price, index)"
	            v-on:input="valueChanged(`item-${value[index].selection.id}`, value[index].selection.price, index)"
	        >
    	</div>
    	<div v-show="false">
	        <small>TBD field: </small>
	        <input
                v-model="value[index].tbd"
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
    </div>
    <div class="col-md-2" v-if="value[index].selection">
        <h5>Stock: </h5>
        <h4> @{{ value[index].selection.quantity }} @{{ value[index].selection.product.measurement_unit.name }}</h4>
    </div>
</div>
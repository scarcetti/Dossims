{{-- <input :name="`item-${item.id}-price`" :value="item.price" hidden /> --}}
<input :name="`item-${item.id}-price`" :value="item.price_at_purchase" hidden />
<div :class="`cartContainer item-${item.id}`">
    <div class="col-md-6">
        <div>
            <small>Product name: </small>
            <h4 style="margin: 0 0 10px 0">@{{ item.product_name }}</h4>
        </div>
        <div>
            <small>Item price: </small>
            <h4 style="margin: 0">₱ @{{ item.price_at_purchase }}</h4>
        </div>
    </div>
    <div class="col-md-2">
        <div>
            {{-- <small>@{{ item.branch_product.product.measurement_unit.name }}: </small> --}}
            <small>Quantity: </small>
            <input
                class="form-control"
                readonly
                :value="item.quantity"
                type="number"
                min="0"
                style="margin: 0 0 6px 0"
            >
        </div>
        <div>
            <small>Subtotal: </small>
            <span v-if="item.discount_value" style="display: flex;">
                <h4 class="subtotal" style="color:#d5d5d5; margin: 0"><s>₱ @{{ (item.price_at_purchase * item.quantity).toFixed(2) }}</s></h4>&nbsp;&nbsp;&nbsp;
                <h4 class="subtotal" style="margin: 0">₱ @{{ item.discount_value }}</h4>
            </span>
            <span v-else style="display: flex;">
                <h4 class="subtotal" style="margin: 0">₱ @{{ (item.price_at_purchase * item.quantity).toFixed(2) * (item.linear_meters ? item.linear_meters : 1) }}</h4>
            </span>
        </div>
    </div>
    <div class="col-md-2" style="align-self: self-start; margin-top: 7px;">
        <div v-if="item.branch_product.product.measurement_unit.name == 'Linear Meter'">
            <small>Linear meters: </small>
            <input
                class="form-control"
                readonly
                :value="item.linear_meters"
                type="number"
                style="margin: 0 0 6px 0"
            >
        </div>
        <div v-show="false">
            <small>TBD field: </small>
            <input
                class="form-control"
                readonly
                :value="item.tbd"
                type="number"
                style="margin: 0 0 6px 0"
            >
        </div>
    </div>
    <div class="col-md-2">
        <span v-if="transaction.status == 'waiting for payment' && !item.discount" v-on:click="discountDialogShow(item.id)" class="btn btn-warning edit">Add discount</span>
        <span v-if="transaction.status == 'waiting for payment' && item.discount" v-on:click="discountDialogShow(item.id)" class="btn btn-warning edit">View discount</span>
        <span v-if="transaction.status == 'procuring' && item.discount" v-on:click="discountDialogShow(item.id)" class="btn btn-warning edit">View discount</span>
        <span v-if="transaction.status == 'procuring' && !item.discount" class="btn" readonly style="background: #cbc0b3; color: white;">Not discounted</span>
    </div>
    {{-- @{{item.discount}} --}}
    <div class="modal fade" :id="`discountDialog${item.id}`" tabindex="-1" role="dialog" aria-labelledby="dialogLabel" aria-hidden="true">
        <div class="modal-success-dialog modal-dialog" role="document" style="height: 100%; display: flex; flex-direction: column; justify-content: center;">
            <div class="modal-content">
                <div class="modal-header" style="display: flex; align-items: center;">
                    <h5 class="modal-title" id="dialogLabel">Set discounts for @{{ item.product_name }}</h5>
                    <button type="button" {{-- @click="closeImage" --}} class="close" data-dismiss="modal" aria-label="Close" style="margin-left: auto;">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <hr style="margin: 0">
                <div class="modal-body" style="padding-top: 0px !important; padding-left: 5%; padding-right: 5%; max-height: 70vh;">
                    <div class="form-group  col-md-12" style="padding: 0;">
                        <label class="control-label" for="name">Discount value <i style="color:red;">&nbsp;&nbsp;&nbsp;Discount has no effect when discount value is empty</i></label>
                        <input
                            v-if="item.discount"
                            :name="`item-${item.id}-discount-value`"
                            class="form-control"
                            type="number"
                            min="0"
                            v-model="item.discount.value"
                            style="margin: 0 0 6px 0"
                            readonly
                        >
                        <input
                            v-else
                            v-model="value[index].pre_discount"
                            v-on:change="discountsModified(item, index)"
                            v-on:input="discountsModified(item, index)"
                            :name="`item-${item.id}-discount-value`"
                            class="form-control"
                            type="number"
                            min="0"
                            style="margin: 0 0 6px 0"
                        >
                    </div>

                    <div class="form-group  col-md-12 ">
                        <h5>Type of discount</h5>
                        <ul v-if="transaction.status == 'waiting for payment'" class="radio" v-on:click="discountsModified(item, index)">
                            <li>
                                <input v-model="value[index].discount_type" type="radio" :id="`item-${item.id}-option-type-fixed`" :name="`item-${item.id}-discount-type`" value="fixed">
                                <label :for="`item-${item.id}-option-type-fixed`">Fixed amount</label>
                                <div class="check"></div>
                            </li>
                            <li>
                                <input v-model="value[index].discount_type" type="radio" :id="`item-${item.id}-option-type-percentage`" :name="`item-${item.id}-discount-type`" value="percentage">
                                <label :for="`item-${item.id}-option-type-percentage`">Percentage</label>
                                <div class="check"></div>
                            </li>
                        </ul>
                        <ul v-if="transaction.status == 'procuring' && item.discount">
                            <li><label>@{{ item.discount.fixed_amount ? 'Fixed amount' : 'By percentage' }}</label></li>
                            <li><label>@{{ item.discount.per_item ? `Applied per ${item.branch_product.product.measurement_unit.name}` : 'Applied on the subtotal' }}</label></li>
                        </ul>
                    </div>
                    <div v-if="transaction.status == 'waiting for payment'" style="margin: 0 10px">
                        <h5>Per item <small><br>@{{ cbNote3 }}</small></h5>
                        <label class="switch">
                            <input v-model="value[index].discount_per_item" :name="`item-${item.id}-discount-type-per-item`" type="checkbox" v-on:click="discountsModified(item, index)">
                            <div class="slider round"></div>
                        </label>
                    </div>
                    <div v-if="transaction.status == 'waiting for payment' && !item.discount && value[index].pre_discount > 0" style="text-align-last: right;">
                        <span v-on:click="applyDiscount(item, index)" class="btn btn-success edit" data-dismiss="modal" aria-label="Close">Apply discount</span>
                        <span v-on:click="removeDiscount(item.id, index)" class="btn btn-danger edit" data-dismiss="modal" aria-label="Close">Remove discount</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div>
    <div style="margin: 30px 0 15px 0;">
        <input v-if="customer.value" name="customer_id" :value="customer.value.id" hidden/>
        {{-- @{{ customer.value }} --}}
        <label :class="`control-label ${businessCustomer.value ? '' : 'rr'}`" for="name">Customer</label>
        <multiselect
            v-model="customer.value"
            @input="businessCustomer.value = null"
            deselect-label="Remove"
            track-by="id"
            :custom-label="customNameLabel"
            :options="customer.options[0]"
            placeholder="Select customer"
            :searchable="true"
            :allow-empty="true"
        />
    </div>
    <div v-if="customer.value && customer.value.balance">
        {{-- @{{ customer.value.balance}} --}}
        <span>
            <i style="color: red;">
                Has remaining balance.
                <button type="subtypemit" class="btn btn-primary save" @click="viewBalance(customer.value.balance.id)">Make full payment</button>
            </i>
        </span>
    </div>
    <div v-if="customer.value && !customer.value.balance" style="margin: 30px 0 15px 0;">
        <input v-if="businessCustomer.value" name="business_customer_id" :value="businessCustomer.value.id" hidden/>
        <label :class="`control-label ${customer.value ? '' : 'rr'}`" for="name">Business Customer</label>
        <multiselect
            v-model="businessCustomer.value"
            @input="customer.value = null"
            deselect-label="Remove"
            track-by="id"
            label="name"
            :options="businessCustomer.options[0]"
            {{-- :custom-label="customNameLabel"
            :options="customer.options[0]" --}}
            placeholder="Select business customer"
            :searchable="true"
            :allow-empty="true"
        />
    </div>
    <div v-if="customer.value && !customer.value.balance" style="margin: 30px 0 15px 0;">
        <input v-if="employee.value" name="employee_id" :value="employee.value.id" hidden/>
        <label class="control-label rr" for="name">Employee</label>
        <multiselect
            v-model="employee.value"
            {{-- @input="customer.value = []" --}}
            deselect-label="Remove"
            track-by="id"
            {{-- label="employee.first_name" --}}
            :custom-label="customEmployeeLabel"
            placeholder="Select operating employee"
            :options="employee.options[0]"
            :searchable="true"
            :allow-empty="false"
        />
    </div>
</div>

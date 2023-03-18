<div>
    <div style="margin: 30px 0 15px 0;">
        <input v-if="customer.value" name="customer_id" :value="customer.value.id" hidden/>
        {{-- @{{ customer.value }} --}}
        <label class="control-label" for="name">Customer</label>
        <multiselect
            v-model="customer.value"
            @input="businessCustomer.value = []"
            deselect-label="Remove"
            track-by="id"
            :custom-label="customNameLabel"
            placeholder="Select customer"
            :options="customer.options[0]"
            :searchable="true"
            :allow-empty="true"
        />
    </div>
    {{-- <div v-if="customer.value">
        @{{ customer.value.balance}}
        <div v-if="customer.value.balance">
            <span>Has no pending balance</span>
        </div>
        <div v-else>
            <span>Has pending balance</span>
        </div>
    </div> --}}
    <div style="margin: 30px 0 15px 0;">
        <input v-if="businessCustomer.value" name="business_customer_id" :value="businessCustomer.value.id" hidden/>
        <label class="control-label" for="name">Business Customer</label>
        <multiselect
            v-model="businessCustomer.value"
            @input="customer.value = []"
            deselect-label="Remove"
            track-by="id"
            label="name"
            placeholder="Select business customer"
            :options="businessCustomer.options[0]"
            :searchable="true"
            :allow-empty="true"
        />
    </div>
    <div style="margin: 30px 0 15px 0;">
        <input v-if="employee.value" name="employee_id" :value="employee.value.id" hidden/>
        <label class="control-label" for="name">Employee</label>
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
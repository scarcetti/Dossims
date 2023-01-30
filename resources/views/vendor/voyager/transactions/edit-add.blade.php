@extends('voyager::bread.edit-add')
@section('submit-buttons')
    <div id="app" style="margin: 0 15px;">
       {{--  <div>
            {{ $branches }}
        </div> --}}
        {{-- <div>
            {{ $branch_products ?? '' }}
        </div> --}}
        <div>
            <multiselect
                v-model="value"
                placeholder="Select Products"
                label="product_name"
                track-by="id"
                :options={!! $branch_products ?? '' !!}
                :multiple="true"
                {{-- :taggable="true" --}}
            ></multiselect>

            <div style="
                max-height: 500px;
                overflow: auto;
                margin-top: 20px;
            ">
                <div
                    v-for="(item, index) in value"
                    :key="index"
                    style="
                        border: 1px solid #e8e8e8;
                        border-radius: 5px;
                        padding: 8px;
                        margin: 0 0 20px 0;
                    "
                >
                    {{-- @{{ item }} --}}
                    <div >
                        <div>
                            <small>Product name: </small>
                            <h4 style="margin: 0 0 10px 0">@{{ item.product_name }}</h4>
                        </div>
                        <div>
                            <small>Item price: </small>
                            <h4 style="margin: 0">@{{ item.product.price }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    @parent
@endsection
@section('javascript')
    @parent
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://unpkg.com/vue-multiselect@2.1.0"></script>
    <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>

    <script type="module">
        // import VueNumberInput from '@chenfengyuan/vue-number-input';
        // window.VueNumberInput = require('@chenfengyuan/vue-number-input');
        var app = new Vue({
            el: '#app',
            components: { 
                Multiselect: window.VueMultiselect.default,
                // VueNumberInput.name: VueNumberInput
            },
            data () {
                return {
                    value: [],
                }
            },
            created() {}
        })
    </script>
@endsection
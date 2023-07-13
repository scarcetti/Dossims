@extends('voyager::master')
@section('content')
    <style>
        section {
            display: flex;
            background: #0000001a;
            margin: 10px;
            padding: 10px;
        }

        .formField {
            display: flex;
            flex-direction: column;
            margin: 5px 10px;
        }
    </style>

    <div id="reports">
        <section>
            <form method="GET" action="/printouts/sales-report" enctype="multipart/form-data">
                <div style="width: 100%;">
                    <div>
                        <h4>Sales Report</h4>
                    </div>
                    <div style="display: flex;">
                        <span class="formField">
                            <label for="monthYear_start1">Starting month:</label>
                            <input id="monthYear_start1" name="m_y_start1" type="month" v-model="monthYearStart1" style="height: 40px;">
                        </span>
                        <span class="formField">
                            <label for="monthYear_end1">Ending month:</label>
                            <input id="monthYear_end1" name="m_y_end1" type="month" v-model="monthYearEnd1" style="height: 40px;">
                        </span>
                        <span class="formField">
                            <label>Select Branch:</label>
                            <input v-if="branch.id" name="branch_id" type="hidden" :value="branch.id">
                            <multiselect
                                v-model="branch"
                                deselect-label="Can't remove this value"
                                track-by="name"
                                label="name"
                                placeholder="Select Branch"
                                :options="branches"
                                :searchable="true"
                                :allow-empty="false"
                                style="min-width: 20vw;"
                            />
                        </span>
                    </div>

                    <div>
                        <button :disabled="monthYearStart1 && monthYearEnd1 && !branch.id" class="btn btn-sm btn-primary pull-left edit" type="submit" style="margin-top: 5px;">Generate PDF</button>
                    </div>
                </div>
            </form>
        </section>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://unpkg.com/vue-multiselect@2.1.0"></script>
    <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">

    <script>
        new Vue({
            el: '#reports',
            components: {
                Multiselect: window.VueMultiselect.default,
            },
            data () {
                return {
                    monthYearStart1: '',
                    monthYearEnd1: '',
                    branch: [],
                    branches: {!! $branches ?? '' !!},
                }
            },
            methods: {
                initialDate() {
                    var currentDate = new Date();
                    var currentMonth = (currentDate.getMonth() + 1).toString().padStart(2, '0');
                    var currentYear = currentDate.getFullYear().toString();
                    this.monthYearStart1 = currentYear + '-' + currentMonth
                    this.monthYearEnd1 = currentYear + '-' + currentMonth
                },

            },
            created() {
                this.initialDate()

            }
        })
    </script>
@endsection

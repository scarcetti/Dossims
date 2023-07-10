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
                            <label for="startDate1">Select starting date:</label>
                            {{-- <input id="monthYear1" name="m_y" type="month" v-model="monthYear" style="height: 40px;"> --}}
                            <input id="startDate1" name="start1" type="date" v-model="startDate1">
                        </span>
                        <span class="formField">
                            <label for="endDate1">Select end date:</label>
                            {{-- <input id="monthYear1" name="m_y" type="month" v-model="monthYear" style="height: 40px;"> --}}
                            <input id="endDate1" name="end1" type="date" v-model="endDate1">
                        </span>
                        @{{ startDate1 }}
                        <span class="formField">
                            <label>Select Branch:</label>
                            <input v-if="branch.id" name="branch_id" type="hidden" :value="branch.id">
                            <multiselect v-model="branch" deselect-label="Can't remove this value" track-by="name"
                                label="name" placeholder="Select Branch" :options="branches" :searchable="true"
                                :allow-empty="false" style="min-width: 20vw;" />
                        </span>
                    </div>

                    <div>
                        <button :disabled="startDate1 && endDate1 && !branch.id"
                            class="btn btn-sm btn-primary pull-left edit" type="submit" style="margin-top: 5px;">Generate
                            PDF</button>
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
            data() {
                return {
                    startDate1: '',
                    endDate1: '',
                    branch: [],
                    branches: {!! $branches ?? '' !!},
                }
            },
            methods: {
                initialDate() {
                    var currentDate = new Date();
                    var year = currentDate.getFullYear();
                    var month = ("0" + (currentDate.getMonth() + 1)).slice(-
                        2); // Adding 1 to the month because it is zero-based

                    // Get the starting day of the month
                    var startingDay = "01";

                    // Get the final day of the month
                    var finalDay = new Date(year, currentDate.getMonth() + 1, 0).getDate();

                    // Format the starting day and final day
                    var formattedStartingDay = year + "-" + month + "-" + startingDay;
                    var formattedFinalDay = year + "-" + month + "-" + finalDay;

                    this.startDate1 = formattedStartingDay
                    this.endDate1 = formattedFinalDay
                },

            },
            created() {
                this.initialDate()

            }
        })
    </script>
@endsection

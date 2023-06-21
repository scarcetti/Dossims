@extends('voyager::master')
@section('content')

<div id="app">
    <section>
        <div class="containers_">
            <form id="filters" style="width: -webkit-fill-available; display: flex; align-items: center;">
                <div class="col-md-3">
                    <input name="branch_id" v-model="activeBranch.id" hidden/>
                    <multiselect
                        v-model="activeBranch"
                        @input="branchSelected()"
                        deselect-label="Can't remove this value"
                        track-by="name"
                        label="name"
                        placeholder="Select Branch"
                        :options="branches"
                        :searchable="false"
                        :allow-empty="false"
                    />
                </div>
                <div class="col-md-9">
                    <div class="input-group col-md-12">
                        <input type="text" class="form-control" placeholder="Search Product" name="search_input" v-on:keyup.13="setFilters()" v-model="searchinput">
                        <span class="input-group-btn" style="width: 15px;">
                            <span class="btn btn-info btn-lg" @click="setFilters()" style="margin:0;">
                                <i class="voyager-search"></i>
                            </span>
                        </span>
                    </div>
                </div>
                <div class="col-md-2">
                    <span class="btn btn-primary" @click="clearFilters()">Clear filters
                    </span>
                </div>
            </form>
        </div>
        <div class="paginator_ containers_">
            <table style="width: 100%;">
                <thead>
                    <tr/>
                        <th v-if="!activeBranch.id">Branch</th>
                        <th>Product name</th>
                        <th>Stocks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($branch_products as $item)
                        <tr style="border-top: solid #5c5c5c29 1px">
                            <td v-if="!activeBranch.id">{{ $item->branch->name }}</td>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->quantity }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2">No record</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <br><br>
            {{-- {{ $branch_products->links() }} --}}
        </div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="https://unpkg.com/vue-multiselect@2.1.0"></script>
<link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">
<script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/inventory.css') }}">

<script type="module">
    var app = new Vue({
        el: '#app',
        components: {
            Multiselect: window.VueMultiselect.default,
        },
        data () {
            return {
                searchinput: '',
                activeBranch: [],
                branches: {!! $branches ?? '' !!},
            }
        },
        methods: {
            setFilters() {
                const filters = {
                    branch: this.activeBranch,
                    search_input: this.searchinput,
                }
                localStorage.setItem('filters', JSON.stringify(filters))
                setTimeout(()=>{
                    $('form#filters').submit()
                },50)
            },
            paginated(destination_page) {
                if ( Number.isInteger(destination_page) ) { // when paginate buttons are clicked
                  filter.page = destination_page
                }
                else { // when filters are interacted
                  if (current_page) { // when filter returns only 1 page
                    filter.page = 1
                  }
                  else {
                    filter.page = 1
                  }
                }
                console.log(destination_page)

                setTimeout(()=>{
                    $('form#filters').submit()
                },50)
            },
            branchSelected() {
                this.setFilters()
            },
            applyFilters() {
                let filters = localStorage.getItem('filters')
                if(filters) {
                    filters = JSON.parse(filters)

                    if(filters.branch.id) this.activeBranch = Object.assign({}, this.activeBranch, filters.branch)
                    this.searchinput = filters.search_input
                }
            },
            clearFilters() {
                localStorage.removeItem('filters')
                window.location.href = window.location.origin + window.location.pathname
                // $('form#filters').submit()
            },
            submitForm(destination_page) {
                const current_page = document.querySelector("ul.pagination li.active span")
                let filter = {
                  municipality: this.municipality,
                  search_input: this.searchinput,
                }

                if ( Number.isInteger(destination_page) ) { // when paginate buttons are clicked
                  filter.page = destination_page
                }
                else { // when filters are interacted
                  if (current_page) { // when filter returns only 1 page

                    /*console.log('current_page', Number(current_page.innerHTML))
                    console.log('destination_page', destination_page)

                    alert(`onSubmit: ${Number(current_page.innerHTML)}`)
                    filter.page = Number(current_page.innerHTML)*/
                    filter.page = 1
                  }
                  else {
                    filter.page = 1
                  }
                }
                document.querySelector("input[name='page']").value = filter.page

                if (!current_page) { // when filter returns only 1 page
                  document.querySelector("input[name='page']").value = 1
                }

                localStorage.setItem('vaccination_request_filters', JSON.stringify(filter))
                document.querySelector("form.form-search").submit()
            },
        },
        mounted() {
            this.applyFilters()
        }
    })
</script>
{{-- vanilla js because I do not suck at js. git gud --}}
<script>
  function onPaginationClick(page) {
    comsole.log(page)
    app.submitForm(Number(page))
  }

  function setEventLIstener() {
    const paginationButtons = document.querySelector("ul.pagination")
    if (paginationButtons) {
      for(let i in paginationButtons.children) {
        if(paginationButtons.children[i].firstChild) {
          const button = paginationButtons.children[i].firstChild
          if(button.href) {
            const page = button.href.split("=")[1]

            button.addEventListener("click", () => { onPaginationClick(page) })
            button.removeAttribute("href")
          }
        }
      }
    }
  }

  setEventLIstener()
</script>
@endsection

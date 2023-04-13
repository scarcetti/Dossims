@extends('voyager::master')
@section('content')

<div id="app">
    <section>
        <div class="paginator_ containers_">
            <table style="width: 100%;">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Balance</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($balances as $item)
                    {{-- {{ $item }} --}}
                        <tr style="border-top: solid #5c5c5c29 1px">
                            {{-- <td v-if="!activeBranch.id">{{ $item->branch->name }}</td>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->quantity }}</td> --}}
                            <td>
                                {{ $item->customer->first_name . ' ' . $item->customer->last_name }}
                            </td>
                            <td>
                                {{ $item->outstanding_balance }}
                            </td>
                            <td>
                                {{-- {{ $item }} --}}
                                <button @click="view({{ $item->id }})" type="submit" class="btn btn-primary save">View</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">No record</td>
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

            }
        },
        methods: {
            view(x) {
                console.log(x)

                location.href = `${location.origin}/admin/balances/${x}`
            }
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

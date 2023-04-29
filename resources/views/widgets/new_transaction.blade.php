<div class="panel widget center bgimage" style="margin-bottom:0;overflow:hidden;background-image:url('/widget-bg/transaction.jpg');">
    <div class="dimmer"></div>
    <div class="panel-content">
        <i class="voyager-credit-card"></i><h4>Transactions</h4>
        <p>Manage transactions</p>
        <div class="btns">
            @if(!in_array(Auth::user()->role->name, ['branch_manager', 'general_manager']))
            <a href="{{ env('APP_URL') }}/admin/transactions/create" class="btn btn-primary">New Quotation</a>
            @endif
            <a href="{{ env('APP_URL') }}/admin/transactions" class="btn btn-primary">View Transactions</a>
        </div>
    </div>
</div>
<style>
.btns a {
    margin-left: 5px;
    margin-right: 5px;
}
</style>

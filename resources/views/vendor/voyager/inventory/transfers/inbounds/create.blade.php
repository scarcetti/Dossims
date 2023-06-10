<div class="modal fade" id="createInboundDialog" tabindex="-1" role="dialog" aria-labelledby="dialogLabel"
    aria-hidden="true">
    <div class="modal-success-dialog modal-dialog" role="document"
        style="height: 100%; display: flex; flex-direction: column; justify-content: center;">
        <div class="modal-content">
            <div class="modal-header" style="display: flex; align-items: center;">
                <h5 class="modal-title" id="dialogLabel">Create Inbound</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    style="margin-left: auto;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"
                style="padding-top: 0px !important; padding-left: 5%; padding-right: 5%; max-height: 70vh;">
                <input name="branch_id" value="{{ branch_() }}" hidden />
                <div class="col-md-6">
                    <small class="rr">Referrer</small>
                    <input class="form-control" placeholder="enter referrer's name" type="text" min="0"
                        style="margin: 0 0 6px 0">
                </div>
                <div class="col-md-6">
                    <small class="rr">Referrer Contact</small>
                    <input class="form-control" placeholder="+639 ..." type="text" min="0"
                        style="margin: 0 0 6px 0">
                </div>
                <br><br>
                <div class="col-md-12" style="height: 300px; overflow: scroll;">
                    {{-- <small class="rr">Stocks</small> --}}
                    @include('voyager::inventory.transfers.inbounds.dynamic-list')
                </div>
            </div>
        </div>
    </div>
</div>

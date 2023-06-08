<div class="modal fade" id="createOutboundDialog" tabindex="-1" role="dialog" aria-labelledby="dialogLabel"
    aria-hidden="true">
    <div class="modal-success-dialog modal-dialog" role="document"
        style="height: 100%; display: flex; flex-direction: column; justify-content: center;">
        <div class="modal-content">
            <div class="modal-header" style="display: flex; align-items: center;">
                <h5 class="modal-title" id="dialogLabel">Create Outbound</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    style="margin-left: auto;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"
                style="padding-top: 0px !important; padding-left: 5%; padding-right: 5%; max-height: 70vh;">
                <div class="col-md-12">
                    <small class="rr">Destination Branch</small>
                    <input name="branch_id" v-model="activeBranch.id" hidden />
                    <multiselect v-model="activeBranch" @input="branchSelected()" deselect-label="Can't remove this value"
                        track-by="name" label="name" placeholder="Select Branch" :options="branches"
                        :searchable="false" :allow-empty="false" />
                </div>
                <br><br><br>
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
            </div>
        </div>
    </div>
</div>

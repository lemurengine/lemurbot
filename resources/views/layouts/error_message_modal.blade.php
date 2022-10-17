<div class="modal" id="errorMessageModal" tabindex="-1" role="dialog" data-test='{!! $htmlTag !!}-error-message-modal'>
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" id="{!! $htmlTag !!}-error-message-title"></h4>
                    <div class="clearfix"></div>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger"><p id="{!! $htmlTag !!}-error-message-content"></p></div>
                    <div class="clearfix"></div>
                </div>
                <div class="modal-footer">
                    <!-- Submit Field -->
                    <div class="form-group col-sm-12">
                        <button type="button" class="btn btn-secondary"  data-test="{!! $htmlTag !!}-error-message-modal-close" data-dismiss="modal">Close</button>
                    </div>
                    <div class="clearfix"></div>
                </div>

        </div>
    </div>
</div>


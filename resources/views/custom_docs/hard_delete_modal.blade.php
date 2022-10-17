<div class="modal" id="hardDeleteModal" tabindex="-1" role="dialog" data-test='{!! $htmlTag !!}-hard-delete-modal'>
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action='' method='POST' data-test='{!! $htmlTag !!}-hard-delete-form' class='validate' name='{!! $htmlTag !!}-hard-delete'>
                <input name="_method" type="hidden" value="DELETE">
                <input name="hard_delete" type="hidden" value="1">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">{!! $title !!} Hard Delete</h4>
                    <div class="clearfix"></div>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">This will permanently remove this item [id: <span id="itemId"></span>]
                        <br/>This cannot be undone.
                        <br/>Are you sure you want to continue?</div>
                    {{ Form::hidden('redirect_url', url()->current(),['data-test'=>"{$htmlTag}-redirect-url"]) }}
                    {{ Form::hidden('hard_delete', 1,['data-test'=>"{$htmlTag}-hard-delete"]) }}
                    <div class="clearfix"></div>
                </div>
                <div class="modal-footer">
                    <!-- Submit Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::submit('Delete', ['class' => 'btn btn-danger', 'data-test'=>"{$htmlTag}-hard-delete-form-submit"]) !!}
                        <button type="button" class="btn btn-secondary"  data-test="{!! $htmlTag !!}-hard-delete-modal-close" data-dismiss="modal">Cancel</button>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        $(document).ready(function() {

            $(document).on('click','.openHardDeleteDataTableModal',function(){
                var data_id= $(this).attr('data-id');
                var url = "/sa/<?php echo $link;?>/"+data_id;
                $('div#hardDeleteModal form').attr('action',url)
                $('span#itemId').text(data_id)
                $('div#hardDeleteModal').modal('show');

            });

        });
    </script>
@endpush

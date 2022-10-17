<div class="modal" id="restoreModal" tabindex="-1" role="dialog" data-test='{!! $htmlTag !!}-restore-modal'>
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action='' method='POST' data-test='{!! $htmlTag !!}-restore-form' class='validate' name='{!! $htmlTag !!}-restore'>
                <input name="_method" type="hidden" value="PATCH">
                <input name="restore" type="hidden" value="1">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">{!! $title !!} Restore</h4>
                    <div class="clearfix"></div>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">Are you sure you want to restore this? [id: <span id="itemId"></span>]</div>
                    {{ Form::hidden('redirect_url', url()->current(),['data-test'=>"{$htmlTag}-redirect-url"]) }}
                    <div class="clearfix"></div>
                </div>
                <div class="modal-footer">
                    <!-- Submit Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::submit('Restore', ['class' => 'btn btn-primary', 'data-test'=>"{$htmlTag}-restore-form-submit"]) !!}
                        <button type="button" class="btn btn-secondary"  data-test="{!! $htmlTag !!}-restore-modal-close" data-dismiss="modal">Cancel</button>
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

            $(document).on('click','.openRestoreModal',function(){
                var data_id= $(this).attr('data-id');
                var url = "/sa/<?php echo $link;?>/restore/"+data_id;
                $('div#restoreModal form').attr('action',url)
                $('span#itemId').text(data_id)
                $('div#restoreModal').modal('show');
            });

        });
    </script>
@endpush

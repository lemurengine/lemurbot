<div class="modal" id="deleteDataTableModal" tabindex="-1" role="dialog" data-test='{!! $htmlTag !!}-delete-modal'>
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action='' method='POST' data-test='{!! $htmlTag !!}-delete-form' class='validate' name='{!! $htmlTag !!}-delete'>
                <input name="_method" type="hidden" value="DELETE">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">{!! $title !!} Delete</h4>
                    <div class="clearfix"></div>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">Are you sure you want to delete this - <span id="message"></span>?</div>
                    {{ Form::hidden('redirect_url', url()->current(),['data-test'=>"{$htmlTag}-redirect-url"]) }}
                    <div class="clearfix"></div>
                </div>
                <div class="modal-footer">
                    <!-- Submit Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::submit('Delete', ['class' => 'btn btn-danger', 'data-test'=>"{$htmlTag}-delete-form-submit"]) !!}
                        <button type="button" class="btn btn-secondary"  data-test="{!! $htmlTag !!}-delete-modal-close" data-dismiss="modal">Cancel</button>
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

            $(document).on('click','.openDeleteDataTableModal',function(){
                var data_id= $(this).attr('data-id');
                var url = "/<?php echo $link;?>/"+data_id;
                $('div#deleteDataTableModal span#message').text($(this).attr('data-message'))
                $('div#deleteDataTableModal form').attr('action',url)
                $('div#deleteDataTableModal').modal('show');
            });

        });
    </script>
@endpush

<div class="modal" id="banDataTableModal" tabindex="-1" role="dialog" data-test='{!! $htmlTag !!}-ban-modal'>
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action='' method='POST' data-test='{!! $htmlTag !!}-ban-form' class='validate' name='{!! $htmlTag !!}-ban'>
                <input name="_method" type="hidden" value="PATCH">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Client Ban</h4>
                    <div class="clearfix"></div>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">Are you sure you want to ban - <span id="message"></span>?</div>
                    {{ Form::hidden('is_banned', 1) }}
                    {{ Form::hidden('redirect_url', url()->current(),['data-test'=>"{$htmlTag}-redirect-url"]) }}
                    <div class="clearfix"></div>
                </div>
                <div class="modal-footer">
                    <!-- Submit Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::submit('Ban', ['class' => 'btn btn-danger', 'data-test'=>"{$htmlTag}-ban-form-submit"]) !!}
                        <button type="button" class="btn btn-secondary"  data-test="{!! $htmlTag !!}-ban-modal-close" data-dismiss="modal">Cancel</button>
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

            $(document).on('click','.openBanDataTableModal',function(){
                var data_id= $(this).attr('data-id');
                var url = "/clients/"+data_id;
                $('div#banDataTableModal span#message').text($(this).attr('data-message'))
                $('div#banDataTableModal form').attr('action',url)
                $('div#banDataTableModal').modal('show');
            });

        });
    </script>
@endpush

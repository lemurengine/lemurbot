<div class="modal" id="addModal" tabindex="-1" role="dialog" data-test='{!! $htmlTag !!}-create-modal'>
    <div class="modal-dialog modal-lg add" role="document">
        <div class="modal-content">
            <form action='' method='POST' data-test='{!! $htmlTag !!}-create-form-modal' class='validate' name='{!! $htmlTag !!}-create'>

            <div class="modal-header">
                <h4 class="modal-title">{!! $title !!} Add</h4>
                <div class="clearfix"></div>
            </div>
            <div class="modal-body">
                @include($resourceFolder.'.fields')
                @csrf
                {{ Form::hidden('bot_id', $bot->slug,['data-test'=>"{$htmlTag}-bot_id"]) }}
                {{ Form::hidden('redirect_url', url()->current(),['data-test'=>"{$htmlTag}-create-redirect-url"]) }}
                <div class="clearfix"></div>
            </div>
            <div class="modal-footer">
                <!-- Submit Field -->
                <div class="form-group col-sm-12">
                    {!! Form::submit('Save', ['class' => 'btn btn-primary', 'data-test'=>"{$htmlTag}-create-form-modal-submit"]) !!}
                    <button type="button" class="btn btn-secondary" data-test="{!! $htmlTag !!}-add-modal-close" data-dismiss="modal">Cancel</button>
                </div>
                <div class="clearfix"></div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@push('scripts')
    <script>
        $(document).ready(function() {


            $(document).on('click','#openAddModal',function(){

                //set the page this is posted to
                var url = "/<?php echo $link;?>";
                $('div#addModal form').attr('action',url)

                //coin field - which should be disabled in this form
                $('div#addModal input#bot_id_field').val('<?php echo $bot->id; ?>')
                $('div#addModal input#bot_id_field').prop("type", 'hidden');
                $('div#bot_id_div').hide();




                $('div#addModal').modal('show');
            });


        });
    </script>
@endpush

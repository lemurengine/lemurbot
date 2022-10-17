<div class="modal" id="editSlugModal" tabindex="-1" role="dialog" data-test='{!! $htmlTag !!}-edit-slug-modal'>
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action='' method='POST' data-test='{!! $htmlTag !!}-edit-slug-form' class='validate' name='{!! $htmlTag !!}-edit-slug'>
                <input name="_method" type="hidden" value="PATCH">
                {{ Form::hidden('redirect_url', url()->current(),['data-test'=>"{$htmlTag}-redirect-url"]) }}
                {{ Form::hidden('edit_slug', 1,['id'=>'edit_slug', 'data-test'=>"{$htmlTag}-edit-slug"]) }}
                {{ Form::hidden('original_slug', null,['id'=>'original_slug', 'data-test'=>"{$htmlTag}-edit-slug"]) }}
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">{!! $title !!} Edit Slug</h4>
                    <div class="clearfix"></div>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">Changing this value [id: <span id="itemId"></span>] can have unintended consequences
                        <br/>Only proceed if you know what you are doing!</div>
                    <div class="clearfix"></div>

                    <div class="form-group col-sm-12 col-md-6 col-lg-6" data-test="slug_div">
                        {!! Form::label('slug', 'Slug:', ['data-test'=>"slug_label"]) !!}
                        {!! Form::text('slug', null, ['class' => 'form-control', 'id'=>"slug",  LemurEngine\LemurBot\Models\User::getFormValidation('slug'), 'data-test'=>"slug_field"]) !!}
                    </div>

                    <div class="clearfix"></div>

                </div>
                <div class="modal-footer">
                    <!-- Submit Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::submit('Save', ['class' => 'btn btn-primary', 'data-test'=>"{$htmlTag}-edit-slug-form-submit"]) !!}
                        <button type="button" class="btn btn-secondary"  data-test="{!! $htmlTag !!}-edit-slug-modal-close" data-dismiss="modal">Cancel</button>
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

            $(document).on('click','#openEditSlugDataTableModal',function(){
                var data_id= $(this).attr('data-id');
                var url = "/sa/<?php echo $link;?>/slug/"+data_id;
                $('div#editSlugModal form').attr('action',url)
                $('span#itemId').text(data_id)
                $('input#original_slug').val(data_id)
                $('input#slug').val(data_id)
                $('div#editSlugModal').modal('show');

            });

        });
    </script>
@endpush

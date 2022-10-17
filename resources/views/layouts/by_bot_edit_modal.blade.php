<div class="modal" id="editModal" tabindex="-1" role="dialog" data-test='{!! $htmlTag !!}-edit-modal'>
    <div class="modal-dialog modal-lg edit" role="document">
        <div class="modal-content">
            <form action='' method='POST' data-test='{!! $htmlTag !!}-edit-form' class='validate' name='{!! $htmlTag !!}-edit'>
                <input name="_method" type="hidden" value="PATCH">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">{!! $title !!} Edit</h4>
                    <div class="clearfix"></div>
                </div>
                <div class="modal-body">
                    @include($resourceFolder.'.fields')
                    {{ Form::hidden('redirect_url', url()->current(),['data-test'=>"{$htmlTag}-edit-redirect-url"]) }}
                    <div class="clearfix"></div>
                </div>
                <div class="modal-footer">
                    <!-- Submit Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::submit('Save', ['class' => 'btn btn-primary', 'data-test'=>"{$htmlTag}-edit-form-submit"]) !!}
                        <button type="button" class="btn btn-secondary"  data-test="{!! $htmlTag !!}-edit-modal-close" data-dismiss="modal">Cancel</button>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </form>
        </div>
    </div>
</div>

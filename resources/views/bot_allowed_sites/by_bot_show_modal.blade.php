<div class="modal" id="showModal" tabindex="-1" role="dialog" data-test='{!! $htmlTag !!}-show-modal'>
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{!! $title !!} Show</h4>
                <div class="clearfix"></div>
            </div>
            <div class="modal-body">
                <!-- Name Field -->
                <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="website_url_div">
                    {!! Form::label('website_url', 'Website URL:', ['data-test'=>"website_url_label"]) !!}
                    {!! Form::text('website_url', null, ['readonly'=>'readonly','class' => 'form-control','id'=>"website_url_field", 'data-test'=>"website_url_field"] ) !!}
                </div>
            </div>
            <div class="modal-footer">
                <!-- Submit Field -->
                <div class="form-group col-sm-12">
                    <button type="button" class="btn btn-secondary" data-test="{!! $htmlTag !!}-show-modal-close" data-dismiss="modal">Close</button>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('click','.openShowModal',function(){
                //set the page this is posted to
                $('div#showModal textarea#website_url_field').text(this.dataset.website_url)
                $('div#showModal').modal('show');
            });
        });
    </script>
@endpush

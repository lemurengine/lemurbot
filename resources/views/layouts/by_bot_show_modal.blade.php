<div class="modal" id="showModal" tabindex="-1" role="dialog" data-test='{!! $htmlTag !!}-show-modal'>
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{!! $title !!} Show</h4>
                <div class="clearfix"></div>
            </div>
            <div class="modal-body">
                <pre data-test="{!! $htmlTag !!}-show-body" id="json-body"></pre>
                <div class="clearfix"></div>
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

            $(document).on('click','#openShowModal',function(){
                var data_id= $(this).attr('data-id');
                var url = "/api/<?php echo $link;?>/"+data_id;

                $.get(url , { api_token: '<?php echo Auth::user()->api_token ;?>' }, function (data) {
                    //success data
                    var item = data.data
                    $('div#showModal pre#json-body').text(JSON.stringify(item, null, "\t"))
                    $('div#showModal').modal('show');
                }).fail(function(data) {
                    console.error(data)
                    alert( "oops there has been an error" );
                })
            });

        });
    </script>
@endpush

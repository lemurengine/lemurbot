<div class="modal" id="showModal" tabindex="-1" role="dialog" data-test='{!! $htmlTag !!}-show-modal'>
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{!! $title !!} Show</h4>
                <div class="clearfix"></div>
            </div>
            <div class="modal-body">
                <!-- Name Field -->
                <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="name_div">
                    {!! Form::label('show_name', 'Key Name:', ['data-test'=>"show_name_label"]) !!}
                    {!! Form::text('show_name', null, ['readonly'=>'readonly','class' => 'form-control','id'=>"show_name_field", 'data-test'=>"show_name_field"] ) !!}
                </div>

                <div class="clearfix"></div>


                <!-- Description Field -->
                <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="description_div">
                    {!! Form::label('show_description', 'Description:', ['data-test'=>"show_description_label"]) !!}
                    {!! Form::textarea('show_description', null, ['readonly'=>'readonly','rows'=>3, 'class' => 'form-control','id'=>"show_description_field", 'data-test'=>"show_description_field"] ) !!}
                </div>

                <div class="clearfix"></div>

                <!-- Value Field -->
                <div class="form-group col-sm-12 col-md-6" data-test="email_div">
                    {!! Form::label('show_value', 'Key:', ['data-test'=>"show_value_label"]) !!}
                    {!! Form::text('show_value', null, ['readonly'=>'readonly','class' => 'form-control', 'id'=>"show_value_field", 'data-test'=>"show_value_field"] ) !!}

                </div>
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


            $(document).on('click','.openShowModal',function(){


                //set the page this is posted to

                $('div#showModal textarea#show_description_field').text(this.dataset.description)
                $('div#showModal input#show_name_field').val(this.dataset.name)
                $('div#showModal input#show_value_field').val(this.dataset.value)


                $('div#showModal').modal('show');
            });


        });
    </script>
@endpush

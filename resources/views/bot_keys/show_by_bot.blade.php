<div class="clearfix"></div>
<section id="show-by-bot-{!! $htmlTag !!}-details" class="main-form">




    <!-- Forked Id Field -->
    <div class="content">
        <div class="clearfix"></div>

        <div class="callout callout-warning"><h4>Bot keys are used to grant access to talk non public bots.</h4>The only action you can perform with these keys is talking to a bot.</div>
        @if(count($botKeys)<=0)

            <div class="alert alert-info">There are no bot keys associated with this bot </div>

        @else

                        @foreach($botKeys as $index => $item)


                <div class="form-group col-sm-12 col-md-6" data-test="{!! $item->slug !!}_list_div">
                    {!! Form::label('keyname', 'Key Name:', ['data-test'=>$item->slug."_list_label"]) !!}
                    <div class="input-group">
                        {!! Form::text('keyname', $item->name, ['disabled'=>'disabled','readonly'=>'readonly','class' => 'form-control','maxlength' => 255,'maxlength' => 255,'id'=>$item->slug."_list_field", 'data-test'=>$item->slug."_list_field"] ) !!}
                        <div class="input-group-btn">
                            <button type="button" class='btn btn-info details-bot-key-button openShowModal' data-name="{!! $item->name !!}" data-value="{!! $item->value !!}" data-description="{!! $item->description !!}" id='details_bot_key_button' data-test='details_bot_key_button'><i class='fa fa-info'></i> Details</button>
                            <button type="button" class='btn btn-danger delete-bot-key-button openDeleteDataTableModal' data-message="KeyName: {!! $item->name !!}. This will revoke bot access to anyone using this key" data-id="{!! $item->slug !!}" id='delete_bot_key_button' data-test='delete_bot_key_button'><i class='fa fa-trash'></i> Delete</button>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>



                        @endforeach


        @endif

        </div>
</section>

@include('lemurbot::layouts.by_bot_add_modal')
@include('lemurbot::bot_keys.by_bot_show_modal')
@include('lemurbot::layouts.datatable_delete_modal')

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

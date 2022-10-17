<div class="clearfix"></div>
<section id="show-by-bot-{!! $htmlTag !!}-details" class="main-form">




    <!-- Forked Id Field -->
    <div class="content">
        <div class="clearfix"></div>

        <div class="callout callout-warning"><h4>Bot allowed sites can be specified to prevent access to requests from unauthorised sites.</h4>This sits as an additional layer on top of any CORS rules you have specified.<br/>If you leave this blank then only the CORS rules will be applied.</div>
        @if(count($botAllowedSites)<=0)

            <div class="alert alert-info">There are no bot allowed sites associated with this bot </div>

        @else

                        @foreach($botAllowedSites as $index => $item)


                <div class="form-group col-sm-12 col-md-6" data-test="{!! $item->slug !!}_list_div">
                    {!! Form::label('website_url', 'Website Url:', ['data-test'=>$item->slug."_list_label"]) !!}
                    <form action='/botAllowedSites/{!! $item->slug !!}' method='POST' data-test='{!! $htmlTag !!}-update-url-form' class='validate' name='{!! $htmlTag !!}-update'>
                        <input name="_method" type="hidden" value="PATCH">
                        @csrf
                            {!! Form::hidden('bot_allowed_site_id', $item->slug) !!}
                            {!! Form::hidden('bot_id', $item->bot->slug) !!}
                            {!! Form::hidden('redirect_url', url('/bot/sites/'.$item->bot->slug.'/list')) !!}

                            <div class="input-group">
                                {!! Form::text('website_url', $item->website_url, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255,'id'=>$item->slug."_list_field", 'data-test'=>$item->slug."_list_field"] ) !!}
                                <div class="input-group-btn">
                                    <button input="submit" class='btn btn-success edit-button details-bot-allowed-site-button' data-name="{!! $item->website_url !!}" data-value="{!! $item->value !!}" data-description="{!! $item->description !!}" id='details_bot_allowed_site_button' data-test='details_bot_allowed_site_button'><i class='fa fa-edit'></i> Edit</button>
                                    <button type="button" class='btn btn-danger delete-bot-allowed-site-button openDeleteDataTableModal' data-message="Website: {!! $item->website_url !!}. This will revoke access to this site" data-id="{!! $item->slug !!}" id='delete_bot_allowed_site_button' data-test='delete_bot_allowed_site_button'><i class='fa fa-trash'></i> Delete</button>
                                </div>
                            </div>
                    </form>
                </div>

                <div class="clearfix"></div>



                        @endforeach


        @endif

        </div>
</section>

@include('lemurbot::layouts.by_bot_add_modal')
@include('lemurbot::bot_allowed_sites.by_bot_show_modal')
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

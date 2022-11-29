
        @if(count($allBotPlugins)<=0)

            <div class="alert alert-info">There are no custom plugins associated with this bot </div>

        @else

            {!! Form::open(['route' => 'botPlugins.store', 'data-test'=>$htmlTag.'-bot-plugin-create-form', 'class'=>'validate', 'name'=>$htmlTag.'-bot-plugin-create']) !!}


                    @foreach($allBotPlugins as $bpIndex => $botPlugin)

                                {!! Form::hidden('bulk', 1) !!}
                                {!! Form::hidden('redirect_url', url()->current(),['data-test'=>$botPlugin->plugin_slug."-redirect-url"] ) !!}
                                {!! Form::hidden('bot_id', $bot->slug,['data-test'=>$botPlugin->plugin_slug."-bot_id"] ) !!}

                                {!! Form::hidden('plugin_id['.$bpIndex.']', $botPlugin->plugin_slug,['data-test'=>$botPlugin->plugin_slug."-plugin_id"] ) !!}


                                <div class='form-group col-md-4 col-sm-6 col-xs-12' data-test='{!! $botPlugin->slug !!}_div'>
                                    <label for='{!! $botPlugin->slug !!}_field' data-test='{!! $botPlugin->slug !!}_label'>{!! $botPlugin->slug !!}</label>
                                    <div class='input-group'>
                                        <span class='input-group-addon'>

                                            <input type='hidden' name='linked[{!! $bpIndex !!}]' value='0'>

                                            @if(empty($botPlugin->bot_id) )
                                                @php $checked = '' @endphp
                                            @else
                                                @php $checked = 'checked=\'true\'' @endphp

                                            @endif

                                        <input type='checkbox' name='linked[{!! $bpIndex !!}]' value='1' {!! $checked !!}  id='{!! $botPlugin->slug !!}_link_field' {$validation} data-test='{!! $botPlugin->slug !!}_link_field'>
                                        </span>



                                        <input type='text' value='{!! strtolower($botPlugin->title) !!}' class='form-control' id='{!! $botPlugin->title !!}_value_field' data-test='{!! $botPlugin->title !!}_value_field'>
                                        <div class='input-group-btn'>

                                            <a data-title="{!! ucwords($botPlugin->title) !!}" data-description="{!! $botPlugin->description !!}" id='{!! $botPlugin->plugin_slug !!}_info_button' data-test='{!! $botPlugin->plugin_slug !!}_info_button' class='btn btn-info open-info-button'><i class='fa fa-info-circle'></i></a>


                                            <a href="{!! url('plugins?col=0&q='.$botPlugin->plugin_slug) !!}"class="btn btn-warning show-button" data-test="show-button-0">
                                                <i class="fa fa-tree"></i>
                                            </a>

                                            <a href="{!! url('plugins/'.$botPlugin->plugin_slug.'/edit') !!}" id='{!! $botPlugin->plugin_slug !!}_edit_button' data-test='{!! $botPlugin->plugin_slug !!}_edit_button' class='btn btn-success edit-button'><i class='fa fa-edit'></i></a>
                                        </div>
                                    </div>
                                    <small>id: {!! $botPlugin->plugin_slug !!} </small>
                                </div>




            @endforeach

        <!-- Submit Field -->
            <div class="form-group col-sm-12">
                {!! Form::submit('Save Custom Plugins', ['class' => 'btn btn-primary']) !!}
                <button type="reset" class="btn btn-default">Reset Custom Plugins</button>
            </div>

            {!! Form::close() !!}


        @endif

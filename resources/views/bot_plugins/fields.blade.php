<!-- User Id Field -->
@if( !empty($botPlugin) && !empty($botPlugin->user_id))

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="user_id_div">
        {!! Form::label('user_id', 'Created By:', ['data-test'=>"user_id_label"]) !!}
        {!! Form::text('', $botPlugin->user->email, ['readonly'=>'readonly', 'class' => 'form-control', 'id'=>"user_id_field", 'data-test'=>"user_id_field"]) !!}
    </div>

@endif



<!-- Slug Field -->
@if( !empty($botPlugin) && !empty($botPlugin->slug) )
    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="slug_div">
        {!! Form::label('slug', 'Slug:', ['data-test'=>"slug_label"]) !!}
        {!! Form::text('slug', null, ['class' => 'form-control', 'id'=>"slug_field", 'readonly'=>'readonly', LemurEngine\LemurBot\Models\BotPlugin::getFormValidation('slug'), 'data-test'=>"slug_field"]) !!}
    </div>
@endif
<div class="clearfix"></div>


<!-- If we are calling this from inside a bot page then we want to limit it to the bot in question -->
@if( !empty($bot) && !empty($bot->slug) )

    {!! Form::hidden('bot_id', $bot->slug, ['data-test'=>"bot_id_label"]) !!}

@elseif(!empty($botPlugin) && !empty($botPlugin->bot))
    <!-- Bot Id Field -->
    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="bot_slug_div">
        {!! Form::label('bot_slug', 'Bot:', ['data-test'=>"bot_slug_label"]) !!}
        <div class="input-group">
            {!! Form::text('', $botPlugin->bot->name.' ('.$botPlugin->bot->slug.')', ['disabled'=>'disabled', 'readonly'=>'readonly','class' => 'form-control', 'id'=>"bot_slug_field", 'data-test'=>"bot_slug_field", LemurEngine\LemurBot\Models\BotPlugin::getFormValidation('bot_id')]) !!}
            <div class="input-group-btn">
                @if(!empty($botPlugin)&&!empty($botPlugin->bot_id))
                    <a href="{!!url('/bots/'.$botPlugin->bot->slug) !!}" id='bot_button' class='btn btn-warning' data-test='bot_button'><i class='fa fa-arrow-right'></i></a>
                @endif
            </div>
        </div>
    </div>
    {{\Request::get('bot_id')}}
    {!! Form::hidden('bot_id', $botPlugin->bot->slug, ['data-test'=>"bot_id_hidden"]) !!}
    <div class="clearfix"></div>

@else

    <!-- Bot Id Field -->
    <div class="form-group col-lg-6 col-md-6 col-sm-12 select2" data-test="bot_id_div">
        {!! Form::label('bot_id', 'Bot:', ['data-test'=>"bot_id_label"]) !!}
        {!! Form::select('bot_id', $botList, (!empty($botPlugin)?$botPlugin->bot->slug:(!empty($botPlugin)?$botPlugin->bot->slug:"")), [  'placeholder'=>'Please Select', 'class' => 'form-control select2 generic', LemurEngine\LemurBot\Models\BotPlugin::getFormValidation('bot_id'), 'data-test'=>"$htmlTag-bot_id-select", 'id'=>"$htmlTag-bot_id-select"]) !!}
    </div>

    <div class="clearfix"></div>

@endif

<!-- Plugin Id Field -->
@if(!empty($botPlugin) && !empty($botPlugin->plugin))

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="plugin_id_div">
        {!! Form::label('plugin_id', 'Plugin:', ['data-test'=>"plugin_id_label"]) !!}
        <div class="input-group">
            {!! Form::text('plugin_id', $botPlugin->plugin->title, ['readonly'=>'readonly','class' => 'form-control', 'id'=>"plugin_id_field", 'data-test'=>"plugin_id_field", LemurEngine\LemurBot\Models\SetValue::getFormValidation('plugin_id')]) !!}
            <div class="input-group-btn">
                @if(!empty($botPlugin)&&!empty($botPlugin->plugin_id))
                    <a href="{!!url('/plugins/'.$botPlugin->plugin->slug) !!}" id='botPlugin_button' class='btn btn-warning' data-test='botPlugin_button'><i class='fa fa-arrow-right'></i></a>
                @endif
            </div>
        </div>
    </div>

    <div class="clearfix"></div>

@else

    <!-- Plugin Id Field -->
    <div class="form-group col-lg-6 col-md-6 col-sm-12 select2" data-test="plugin_id_div">
        {!! Form::label('plugin_id', 'Plugin:', ['data-test'=>"plugin_id_label"]) !!}
        {!! Form::select('plugin_id', $pluginList, (!empty($botPlugin)?$botPlugin->botPlugin->slug:(!empty($botPlugin)?$botPlugin->botPlugin->slug:"")), [  LemurEngine\LemurBot\Models\WordSpelling::getFormValidation('plugin_id'), 'placeholder'=>'Please Select', 'class' => 'form-control select2 generic', 'data-test'=>"$htmlTag-plugin_id_select", 'id'=>"$htmlTag-plugin_id_select-select"]) !!}
    </div>

    <div class="clearfix"></div>

@endif

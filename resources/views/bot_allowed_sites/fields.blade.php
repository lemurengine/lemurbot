<!-- User Id Field -->
@if( !empty($botAllowedSite) && !empty($botAllowedSite->user_id))

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="user_id_div">
        {!! Form::label('user_id', 'Created By:', ['data-test'=>"user_id_label"]) !!}
        {!! Form::text('', $botAllowedSite->user->email, ['readonly'=>'readonly', 'class' => 'form-control', 'id'=>"user_id_field", 'data-test'=>"user_id_field"]) !!}
    </div>

    <div class="clearfix"></div>
@endif



<!-- Slug Field -->
@if( !empty($botAllowedSite) && !empty($botAllowedSite->slug) )

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="slug_div">
        {!! Form::label('slug', 'Slug:', ['data-test'=>"slug_label"]) !!}
        {!! Form::text('slug', null, ['class' => 'form-control', 'id'=>"slug_field", 'readonly'=>'readonly', LemurEngine\LemurBot\Models\BotAllowedSite::getFormValidation('slug'), 'data-test'=>"slug_field"]) !!}
    </div>
    <div class="clearfix"></div>

@endif


<!-- If we are calling this from inside a bot page then we want to limit it to the bot in question -->
@if( !empty($bot) && !empty($bot->slug) )

    {!! Form::hidden('bot_id', $bot->slug, ['data-test'=>"bot_id_label"]) !!}

@elseif(!empty($botAllowedSite) && !empty($botAllowedSite->bot))
    <!-- Bot Id Field -->
    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="bot_slug_div">
        {!! Form::label('bot_slug', 'Bot:', ['data-test'=>"bot_slug_label"]) !!}
        <div class="input-group">
            {!! Form::text('', $botAllowedSite->bot->name.' ('.$botAllowedSite->bot->slug.')', ['disabled'=>'disabled', 'readonly'=>'readonly','class' => 'form-control', 'id'=>"bot_slug_field", 'data-test'=>"bot_slug_field", LemurEngine\LemurBot\Models\BotAllowedSite::getFormValidation('bot_id')]) !!}
            <div class="input-group-btn">
                @if(!empty($botAllowedSite)&&!empty($botAllowedSite->bot_id))
                    <a href="{!!url('/bots/'.$botAllowedSite->bot->slug) !!}" id='bot_button' class='btn btn-warning' data-test='bot_button'><i class='fa fa-arrow-right'></i></a>
                @endif
            </div>
        </div>
    </div>
    {{\Request::get('bot_id')}}
    {!! Form::hidden('bot_id', $botAllowedSite->bot->slug, ['data-test'=>"bot_id_hidden"]) !!}
    <div class="clearfix"></div>

@else

    <!-- Bot Id Field -->
    <div class="form-group col-lg-6 col-md-6 col-sm-12 select2" data-test="bot_id_div">
        {!! Form::label('bot_id', 'Bot:', ['data-test'=>"bot_id_label"]) !!}
        {!! Form::select('bot_id', $botList, (!empty($botAllowedSite)?$botAllowedSite->bot->slug:(!empty($botAllowedSite)?$botAllowedSite->bot->slug:"")), [  'placeholder'=>'Please Select', 'class' => 'form-control select2 generic', LemurEngine\LemurBot\Models\BotAllowedSite::getFormValidation('bot_id'), 'data-test'=>"$htmlTag-bot_id-select", 'id'=>"$htmlTag-bot_id-select"]) !!}
    </div>

    <div class="clearfix"></div>

@endif


<!-- Name Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="website_url_div">
    {!! Form::label('website_url', 'Website Url:', ['data-test'=>"website_url_label"]) !!}
    {!! Form::text('website_url', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255, LemurEngine\LemurBot\Models\BotAllowedSite::getFormValidation('website_url'),'id'=>"website_url_field", 'data-test'=>"website_url_field", 'placeholder'=>"https://www.example.com"] ) !!}
</div>

<div class="clearfix"></div>


@push('scripts')
    {{ Html::script('vendor/lemurbot/js/validation.js') }}
    {{ Html::script('vendor/lemurbot/js/select2.js') }}
@endpush


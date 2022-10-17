<!-- User Id Field -->
@if( !empty($botProperty) && !empty($botProperty->user_id))

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="user_id_div">
        {!! Form::label('user_id', 'Created By:', ['data-test'=>"user_id_label"]) !!}
        {!! Form::text('', $botProperty->user->email, ['readonly'=>'readonly', 'class' => 'form-control', 'id'=>"user_id_field", 'data-test'=>"user_id_field"]) !!}
    </div>

    <div class="clearfix"></div>
@endif



<!-- Slug Field -->
@if( !empty($botProperty) && !empty($botProperty->slug) )

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="slug_div">
        {!! Form::label('slug', 'Slug:', ['data-test'=>"slug_label"]) !!}
        {!! Form::text('slug', null, ['class' => 'form-control', 'id'=>"slug_field", 'readonly'=>'readonly', LemurEngine\LemurBot\Models\BotProperty::getFormValidation('slug'), 'data-test'=>"slug_field"]) !!}
    </div>
    <div class="clearfix"></div>

@endif

<!-- If we are calling this from inside a bot page then we want to limit it to the bot in question -->
@if( !empty($bot) && !empty($bot->slug) )

    {!! Form::hidden('bot_id', $bot->slug, ['data-test'=>"bot_id_label"]) !!}

@elseif(!empty($botProperty) && !empty($botProperty->bot))
    <!-- Bot Id Field -->
    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="bot_slug_div">
        {!! Form::label('bot_slug', 'Bot:', ['data-test'=>"bot_slug_label"]) !!}
        <div class="input-group">
            {!! Form::text('bot_slug', $botProperty->bot->name.' ('.$botProperty->bot->slug.')', ['disabled'=>'disabled','readonly'=>'readonly','class' => 'form-control', 'id'=>"bot_slug_field", 'data-test'=>"bot_slug_field", LemurEngine\LemurBot\Models\SetValue::getFormValidation('bot_id')]) !!}
            <div class="input-group-btn">
                @if(!empty($botProperty)&&!empty($botProperty->bot_id))
                    <a href="{!!url('/bots/'.$botProperty->bot->slug) !!}" id='bot_button' class='btn btn-warning' data-test='bot_button'><i class='fa fa-arrow-right'></i></a>
                @endif
            </div>
        </div>
    </div>
    {!! Form::hidden('bot_id', $botProperty->bot->slug, ['data-test'=>"bot_id_hidden"]) !!}
    <div class="clearfix"></div>

@else

    <!-- Bot Id Field -->
    <div class="form-group col-lg-6 col-md-6 col-sm-12 select2" data-test="bot_id_div">
        {!! Form::label('bot_id', 'Bot Id:', ['data-test'=>"bot_id_label"]) !!}
        {!! Form::select('bot_id', $botList, (!empty($botProperty)?$botProperty->bot->slug:(!empty($botProperty)?$botProperty->bot->slug:"")), [  'placeholder'=>'Please Select', 'class' => 'form-control select2 generic', LemurEngine\LemurBot\Models\BotProperty::getFormValidation('bot_id'), 'data-test'=>"$htmlTag-bot_id-select", 'id'=>"$htmlTag-bot_id-select"]) !!}
    </div>

    <div class="clearfix"></div>

@endif


@if(!empty($botProperty) && !empty($botProperty->name))
    @php $readonly = true;@endphp
@else
    @php $readonly = false;@endphp
@endif
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="name_div">
    {!! Form::label('name', 'Name:', ['data-test'=>"name_label"]) !!}
    {!! Form::text('name', null, ['readonly'=>$readonly, 'class' => 'form-control', LemurEngine\LemurBot\Models\BotProperty::getFormValidation('name'),'id'=>"name_field", 'data-test'=>"name_field"] ) !!}
</div>

<div class="clearfix"></div>

<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="value_div">
    {!! Form::label('value', 'Value:', ['data-test'=>"value_label"]) !!}
    {!! Form::text('value', null, ['class' => 'form-control', LemurEngine\LemurBot\Models\BotProperty::getFormValidation('value'),'id'=>"value_field", 'data-test'=>"value_field"] ) !!}
</div>

<div class="clearfix"></div>


<!-- Section Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12 select2" data-test="section_div">
    {!! Form::label('section_id', 'Section:', ['data-test'=>"section_id_label"]) !!}
    {!! Form::select('section_id', $botPropertySectionList, (!empty($botProperty)&&!empty($botProperty->section)?$botProperty->section->slug:(!empty($botProperty)&&!empty($botProperty->section)?$botProperty->section->slug:"")), [  'placeholder'=>'Please select', 'class' => 'form-control select2 first-option', LemurEngine\LemurBot\Models\BotProperty::getFormValidation('section_id'), 'data-test'=>"$htmlTag-section-id-select", 'id'=>"$htmlTag-section-id-select"]) !!}
</div>

<div class="clearfix"></div>


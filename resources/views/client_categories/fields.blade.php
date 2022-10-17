<!-- Slug Field -->
@if( !empty($clientCategory) && !empty($clientCategory->slug) )

    <div class="form-group col-sm-6" data-test="slug_div">
        {!! Form::label('slug', 'Slug:', ['data-test'=>"slug_label"]) !!}
        {!! Form::text('slug', null, ['class' => 'form-control', 'id'=>"slug_field", 'readonly'=>'readonly', LemurEngine\LemurBot\Models\ClientCategory::getFormValidation('slug'), 'data-test'=>"slug_field"]) !!}
    </div>

    <div class="clearfix"></div>


@endif



<!-- Client Id Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="client_id_div">
    {!! Form::label('client_id', 'ClientId:', ['data-test'=>"client_id_label"]) !!}
    <div class="input-group">
        {!! Form::text('client_id', $clientCategory->client->slug, ['readonly'=>'readonly', 'class' => 'form-control', 'id'=>"client_id_field", 'data-test'=>"client_id_field", LemurEngine\LemurBot\Models\Conversation::getFormValidation('client_id')]) !!}
        <div class="input-group-btn">
            @if(!empty($clientCategory)&&!empty($clientCategory->client->slug))
                <a href="{!!url('/clients/'.$clientCategory->client->slug) !!}" id='client_button' class='btn btn-warning' data-test='client_button'><i class='fa fa-arrow-right'></i></a>
            @endif
        </div>
    </div>
</div>

<div class="clearfix"></div>

<!-- Bot Id Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="category_group_id_div">
    {!! Form::label('bot_slug', 'Bot:', ['data-test'=>"bot_slug_label"]) !!}
    <div class="input-group">
        {!! Form::text('', $clientCategory->bot->name.' ('.$clientCategory->bot->slug.')', ['disabled'=>'disabled', 'readonly'=>'readonly','class' => 'form-control', 'id'=>"bot_slug_field", 'data-test'=>"bot_slug_field", LemurEngine\LemurBot\Models\SetValue::getFormValidation('bot_id')]) !!}
        <div class="input-group-btn">
            @if(!empty($clientCategory)&&!empty($clientCategory->bot))
                <a href="{!!url('/bots/'.$clientCategory->bot->slug) !!}" id='bot_button' class='btn btn-warning' data-test='bot_button'><i class='fa fa-arrow-right'></i></a>
            @endif
        </div>
    </div>
</div>

{!! Form::hidden('bot_id', $clientCategory->bot->slug, ['data-test'=>"bot_id_hidden"]) !!}

<div class="clearfix"></div>

<!-- Turn Id Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="turn_id_div">
    {!! Form::label('turn_id', 'TurnId:', ['data-test'=>"turn_id_label"]) !!}
    <div class="input-group">
        {!! Form::text('turn_id', $clientCategory->turn->slug, ['readonly'=>'readonly','class' => 'form-control', 'id'=>"turn_id_field", 'data-test'=>"turn_id_field", LemurEngine\LemurBot\Models\SetValue::getFormValidation('turn_id')]) !!}
        <div class="input-group-btn">
            @if(!empty($clientCategory)&&!empty($clientCategory->turn))
                <a href="{!!url('/turns/'.$clientCategory->turn->slug) !!}" id='turn_button' class='btn btn-warning' data-test='turn_button'><i class='fa fa-arrow-right'></i></a>
            @endif
        </div>
    </div>
</div>

<div class="clearfix"></div>

<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="pattern_div">
    {!! Form::label('pattern', 'Pattern:', ['data-test'=>"pattern_label"]) !!}
    {!! Form::text('pattern', null, ['class' => 'form-control', LemurEngine\LemurBot\Models\ClientCategory::getFormValidation('pattern'),'id'=>"pattern_field", 'data-test'=>"pattern_field"] ) !!}
</div>

<div class="clearfix"></div>

<!-- Template Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="template_div">
    {!! Form::label('template', 'Template:', ['data-test'=>"template_label"]) !!}
    {!! Form::textarea('template', null, ['rows' => 2, 'class' => 'form-control', 'id'=>"template_field", 'data-test'=>"template_field", LemurEngine\LemurBot\Models\ClientCategory::getFormValidation('template')] ) !!}
</div>

<div class="clearfix"></div>

<!-- Slug Field -->
@if( !empty($conversationProperty) && !empty($conversationProperty->slug) )

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="slug_div">
        {!! Form::label('slug', 'Slug:', ['data-test'=>"slug_label"]) !!}
        {!! Form::text('slug', null, ['class' => 'form-control', 'id'=>"slug_field", 'readonly'=>'readonly', LemurEngine\LemurBot\Models\Turn::getFormValidation('slug'), 'data-test'=>"slug_field"]) !!}
    </div>

    <div class="clearfix"></div>

@endif

<!-- Conversation Id Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="conversation_id_div">
    {!! Form::label('conversation_id', 'Conversation Id:', ['data-test'=>"conversation_id_label"]) !!}
    <div class="input-group">
        {!! Form::text('', $conversationProperty->conversation->slug, ['readonly'=>'readonly', 'class' => 'form-control', 'id'=>"conversation_id_field", 'data-test'=>"conversation_id_field", LemurEngine\LemurBot\Models\Turn::getFormValidation('conversation_id')]) !!}
        <div class="input-group-btn">
            @if(!empty($conversationProperty)&&!empty($conversationProperty->conversation_id))
                <a href="{!!url('conversations/'.$conversationProperty->conversation->slug) !!}" id='turn_button' class='btn btn-warning' data-test='turn_button'><i class='fa fa-arrow-right'></i></a>
            @endif
        </div>
    </div>
</div>


<div class="clearfix"></div>

<!-- Value Field -->
<div class="form-group col-sm-6" data-test="name_div">
    {!! Form::label('name', 'Name:', ['data-test'=>"name_label"]) !!}
    {!! Form::text('name', null, ['class' => 'form-control', LemurEngine\LemurBot\Models\ConversationProperty::getFormValidation('name'),'id'=>"name_field", 'data-test'=>"name_field"] ) !!}
</div>


<div class="clearfix"></div>

<!-- Value Field -->
<div class="form-group col-sm-6" data-test="value_div">
    {!! Form::label('value', 'value:', ['data-test'=>"value_label"]) !!}
    {!! Form::text('value', null, ['class' => 'form-control', LemurEngine\LemurBot\Models\ConversationProperty::getFormValidation('value'),'id'=>"value_field", 'data-test'=>"value_field"] ) !!}
</div>

<div class="clearfix"></div>

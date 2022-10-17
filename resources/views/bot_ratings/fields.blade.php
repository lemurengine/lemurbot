<!-- Slug Field -->
@if( !empty($botRating) && !empty($botRating->slug) && strpos('slug', 'slug')!==false)

    <div class="form-group col-sm-6" data-test="slug_div">
        {!! Form::label('slug', 'Slug:', ['data-test'=>"slug_label"]) !!}
        {!! Form::text('slug', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255, 'id'=>"slug_field", 'readonly'=>'readonly', LemurEngine\LemurBot\Models\botRating::getFormValidation('slug'), 'data-test'=>"slug_field"]) !!}
    </div>

@elseif( !empty($botRating) && !empty($botRating->slug) && strpos('slug', 'url')!==false)

    <div class="form-group col-sm-6" data-test="slug_div">
        {!! Form::label('slug', 'Slug:', ['data-test'=>"slug_label"]) !!}
        <div class="input-group">
            {!! Form::text('slug', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255, LemurEngine\LemurBot\Models\botRating::getFormValidation('slug'), 'id'=>"slug_field", 'data-test'=>"slug_field"]) !!}
            <div class="input-group-btn">
                @if(!empty($botRating)&&!empty($botRating->slug))
                    <a href="{!! $botRating->slug !!}" id='slug_button' class='btn btn-warning' data-test='slug_button'><i class='fa fa-arrow-right'></i></a>
                @endif
            </div>
        </div>
    </div>

@else

<div class="form-group col-sm-6" data-test="slug_div">
    {!! Form::label('slug', 'Slug:', ['data-test'=>"slug_label"]) !!}
    {!! Form::text('slug', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255, LemurEngine\LemurBot\Models\botRating::getFormValidation('slug'),'id'=>"slug_field", 'data-test'=>"slug_field"] ) !!}
</div>


@endif


<!-- Conversation Id Field -->
@if( !empty($botRating) && !empty($botRating->conversation_id) && strpos('conversation_id', '_id')!==false)

    @php
        $modelParts = explode('_id', 'conversation_id');
        $goToModel = $modelParts[0]."s";
    @endphp

    <div class="form-group col-sm-3" data-test="conversation_id_div">
        {!! Form::label('conversation_id', 'Conversation Id:', ['data-test'=>"conversation_id_label"]) !!}
        <div class="input-group">
            {!! Form::number('conversation_id', null, ['class' => 'form-control', 'id'=>"conversation_id_field", 'data-test'=>"conversation_id_field", 'data-validation'=>"required"]) !!}
            <div class="input-group-btn">
                @if(!empty($botRating)&&!empty($botRating->conversation_id))
                    <a href="{!!url($goToModel.'/'.$botRating->conversation_id) !!}" id='botRating_button' class='btn btn-warning' data-test='botRating_button'><i class='fa fa-arrow-right'></i></a>
                @endif
            </div>
        </div>
    </div>

@else

<div class="form-group col-sm-3" data-test="conversation_id_div">
    {!! Form::label('conversation_id', 'Conversation Id:', ['data-test'=>"conversation_id_label"]) !!}
    {!! Form::number('conversation_id', null, ['class' => 'form-control', 'id'=>"conversation_id_field", 'data-test'=>"conversation_id_field", 'data-validation'=>"required"]) !!}
</div>

@endif


<!-- Bot Id Field -->
@if( !empty($botRating) && !empty($botRating->bot_id) && strpos('bot_id', '_id')!==false)

    @php
        $modelParts = explode('_id', 'bot_id');
        $goToModel = $modelParts[0]."s";
    @endphp

    <div class="form-group col-sm-3" data-test="bot_id_div">
        {!! Form::label('bot_id', 'Bot Id:', ['data-test'=>"bot_id_label"]) !!}
        <div class="input-group">
            {!! Form::number('bot_id', null, ['class' => 'form-control', 'id'=>"bot_id_field", 'data-test'=>"bot_id_field", 'data-validation'=>"required"]) !!}
            <div class="input-group-btn">
                @if(!empty($botRating)&&!empty($botRating->bot_id))
                    <a href="{!!url($goToModel.'/'.$botRating->bot_id) !!}" id='botRating_button' class='btn btn-warning' data-test='botRating_button'><i class='fa fa-arrow-right'></i></a>
                @endif
            </div>
        </div>
    </div>

@else

<div class="form-group col-sm-3" data-test="bot_id_div">
    {!! Form::label('bot_id', 'Bot Id:', ['data-test'=>"bot_id_label"]) !!}
    {!! Form::number('bot_id', null, ['class' => 'form-control', 'id'=>"bot_id_field", 'data-test'=>"bot_id_field", 'data-validation'=>"required"]) !!}
</div>

@endif


<!-- Rating Field -->
@if( !empty($botRating) && !empty($botRating->rating) && strpos('rating', '_id')!==false)

    @php
        $modelParts = explode('_id', 'rating');
        $goToModel = $modelParts[0]."s";
    @endphp

    <div class="form-group col-sm-3" data-test="rating_div">
        {!! Form::label('rating', 'Rating:', ['data-test'=>"rating_label"]) !!}
        <div class="input-group">
            {!! Form::number('rating', null, ['class' => 'form-control', 'id'=>"rating_field", 'data-test'=>"rating_field", 'data-validation'=>"required"]) !!}
            <div class="input-group-btn">
                @if(!empty($botRating)&&!empty($botRating->rating))
                    <a href="{!!url($goToModel.'/'.$botRating->rating) !!}" id='botRating_button' class='btn btn-warning' data-test='botRating_button'><i class='fa fa-arrow-right'></i></a>
                @endif
            </div>
        </div>
    </div>

@else

<div class="form-group col-sm-3" data-test="rating_div">
    {!! Form::label('rating', 'Rating:', ['data-test'=>"rating_label"]) !!}
    {!! Form::number('rating', null, ['class' => 'form-control', 'id'=>"rating_field", 'data-test'=>"rating_field", 'data-validation'=>"required"]) !!}
</div>

@endif


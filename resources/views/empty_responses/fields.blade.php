<!-- Slug Field -->
@if( !empty($emptyResponse) && !empty($emptyResponse->slug) )

    <div class="form-group col-sm-6" data-test="slug_div">
        {!! Form::label('slug', 'Slug:', ['data-test'=>"slug_label"]) !!}
        {!! Form::text('slug', null, ['class' => 'form-control', 'id'=>"slug_field", 'readonly'=>'readonly', LemurEngine\LemurBot\Models\EmptyResponse::getFormValidation('slug'), 'data-test'=>"slug_field"]) !!}
    </div>
    <div class="clearfix"></div>

@endif

<!-- Conversation Log Id Field -->
@if( !empty($emptyResponse) && !empty($emptyResponse->turn_id) && strpos('turn_id', '_id')!==false)

    @php
        $modelParts = explode('_id', 'turn_id');
        $goToModel = $modelParts[0]."s";
    @endphp

    <div class="form-group col-sm-3" data-test="conversation_log_id_div">
        {!! Form::label('turn_id', 'Conversation Log Id:', ['data-test'=>"conversation_log_id_label"]) !!}
        <div class="input-group">
            {!! Form::number('turn_id', null, ['class' => 'form-control', 'id'=>"conversation_log_id_field", 'data-test'=>"conversation_log_id_field", LemurEngine\LemurBot\Models\EmptyResponse::getFormValidation('turn_id')]) !!}
            <div class="input-group-btn">
                @if(!empty($emptyResponse)&&!empty($emptyResponse->turn_id))
                    <a href="{!!url($goToModel.'/'.$emptyResponse->turn_id) !!}" id='emptyResponse_button' class='btn btn-warning' data-test='emptyResponse_button'><i class='fa fa-arrow-right'></i></a>
                @endif
            </div>
        </div>
    </div>

@else

<div class="form-group col-sm-3" data-test="conversation_log_id_div">
    {!! Form::label('turn_id', 'Conversation Log Id:', ['data-test'=>"conversation_log_id_label"]) !!}
    {!! Form::number('turn_id', null, ['class' => 'form-control', 'id'=>"conversation_log_id_field", 'data-test'=>"conversation_log_id_field", LemurEngine\LemurBot\Models\EmptyResponse::getFormValidation('turn_id')]) !!}
</div>

@endif

<div class="clearfix"></div>

<!-- Raw Input Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="raw_input_div">
    {!! Form::label('raw_input', 'Raw Input:', ['data-test'=>"raw_input_label"]) !!}
    {!! Form::textarea('raw_input', null, ['rows' => 2, 'class' => 'form-control', 'id'=>"raw_input_field", 'data-test'=>"raw_input_field", LemurEngine\LemurBot\Models\EmptyResponse::getFormValidation('raw_input')] ) !!}
</div>
<div class="clearfix"></div>

<!-- Raw That Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="raw_that_div">
    {!! Form::label('raw_that', 'Raw That:', ['data-test'=>"raw_that_label"]) !!}
    {!! Form::textarea('raw_that', null, ['rows' => 2, 'class' => 'form-control', 'id'=>"raw_that_field", 'data-test'=>"raw_that_field", LemurEngine\LemurBot\Models\EmptyResponse::getFormValidation('raw_that')] ) !!}
</div>

<div class="clearfix"></div>
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="raw_topic_div">
    {!! Form::label('raw_topic', 'Raw Topic:', ['data-test'=>"raw_topic_label"]) !!}
    {!! Form::text('raw_topic', null, ['class' => 'form-control', LemurEngine\LemurBot\Models\EmptyResponse::getFormValidation('raw_topic'),'id'=>"raw_topic_field", 'data-test'=>"raw_topic_field"] ) !!}
</div>
<div class="clearfix"></div>

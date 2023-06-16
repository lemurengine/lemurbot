<!-- Slug Field -->
@if( !empty($conversation) && !empty($conversation->slug) )

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="slug_div">
        {!! Form::label('slug', 'ConversationId:', ['data-test'=>"slug_label"]) !!}
        <div class="input-group">
            {!! Form::text('slug', null, ['class' => 'form-control', 'id'=>"slug_field", 'readonly'=>'readonly', LemurEngine\LemurBot\Models\Conversation::getFormValidation('slug'), 'data-test'=>"slug_field"]) !!}
            <div class="input-group-btn">
                <span name='unlock' id='openEditSlugDataTableModal' data-id="{{$conversation->slug}}" data-test='slug-unlock-button'
                      class='btn btn-danger slug-unlock-button'><i class='fa fa-lock'></i></span>
            </div>
        </div>
    </div>


    <div class="clearfix"></div>
@endif


<!-- Bot Id Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="bot_slug_div">
    {!! Form::label('bot_slug', 'Bot:', ['data-test'=>"bot_slug_label"]) !!}
    <div class="input-group">
        {!! Form::text('', $conversation->bot->name.' ('.$conversation->bot->slug.')', ['disabled'=>'disabled', 'readonly'=>'readonly','class' => 'form-control', 'id'=>"bot_slug_field", 'data-test'=>"bot_slug_field", LemurEngine\LemurBot\Models\SetValue::getFormValidation('bot_id')]) !!}
        <div class="input-group-btn">
            @if(!empty($conversation)&&!empty($conversation->bot))
                <a href="{!!url('/bots/'.$conversation->bot->slug) !!}" id='bot_button' class='btn btn-warning' data-test='bot_button'><i class='fa fa-arrow-right'></i></a>
            @endif
        </div>
    </div>
</div>

{!! Form::hidden('bot_id', $conversation->bot->slug, ['data-test'=>"bot_id_hidden"]) !!}
<div class="clearfix"></div>






<!-- Client Id Field -->
    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="client_slug_div">
        {!! Form::label('client_slug', 'Client:', ['data-test'=>"client_slug_label"]) !!}
        <div class="input-group">
            {!! Form::text('client_slug', $conversation->client->slug, ['disabled'=>'disabled','readonly'=>'readonly', 'class' => 'form-control', 'id'=>"client_slug_field", 'data-test'=>"client_slug_field", LemurEngine\LemurBot\Models\Conversation::getFormValidation('client_id')]) !!}
            <div class="input-group-btn">
                @if(!empty($conversation)&&!empty($conversation->client->slug))
                    <a href="{!!url('/clients/'.$conversation->client->slug) !!}" id='client_button' class='btn btn-warning' data-test='client_button'><i class='fa fa-arrow-right'></i></a>
                @endif
            </div>
        </div>
    </div>
{!! Form::hidden('client_id', $conversation->client->slug, ['data-test'=>"client_id_hidden"]) !!}
    <div class="clearfix"></div>

<!-- Allow HTML Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="allow_html_div">
    {!! Form::label('allow_html', 'Allow HTML:', ['data-test'=>"allow_html_label"]) !!}
    <div class="input-group" data-test="allow_html_group">
        <span class="input-group-addon">
            {!! Form::hidden('allow_html', 0) !!}
            @if(empty($conversation) || $conversation->allow_html==0 || !$conversation->allow_html)
                @php $checked = ''; @endphp
            @else
                @php $checked = true; @endphp
            @endif
            {{ Form::checkbox('allow_html', '1', $checked, ['id'=>"allow_html_field", 'data-test'=>"allow_html_field"])  }}
         </span>
         <input type="text" class="form-control" aria-label="..." value="Allow HTML?">
    </div><!-- /.col-lg-6 -->
</div>


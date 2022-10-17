<!-- Slug Field -->
@if( !empty($client) && !empty($client->slug) )

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="slug_div">
        {!! Form::label('slug', 'ClientId:', ['data-test'=>"slug_label"]) !!}
        <div class="input-group">
            {!! Form::text('', $client->slug, ['class' => 'form-control', 'id'=>"slug_field", 'readonly'=>'readonly', 'data-test'=>"slug_field"]) !!}
            <div class="input-group-btn">
                <span name='unlock' id='openEditSlugDataTableModal' data-id="{{$client->slug}}" data-test='slug-unlock-button'
                      class='btn btn-danger slug-unlock-button'><i class='fa fa-lock'></i></span>
            </div>
        </div>

    </div>

@endif

<div class="clearfix"></div>


<!-- Bot Id Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="bot_slug_div">
    {!! Form::label('bot_slug', 'Bot:', ['data-test'=>"bot_slug_label"]) !!}
    <div class="input-group">
        {!! Form::text('', $client->bot->name.' ('.$client->bot->slug.')', ['disabled'=>'disabled', 'readonly'=>'readonly','class' => 'form-control', 'id'=>"bot_slug_field", 'data-test'=>"bot_slug_field", LemurEngine\LemurBot\Models\SetValue::getFormValidation('bot_id')]) !!}
        <div class="input-group-btn">
            @if(!empty($client)&&!empty($client->bot))
                <a href="{!!url('/bots/'.$client->bot->slug) !!}" id='bot_button' class='btn btn-warning' data-test='bot_button'><i class='fa fa-arrow-right'></i></a>
            @endif
        </div>
    </div>
</div>

{!! Form::hidden('bot_id', $client->bot->slug, ['data-test'=>"bot_id_hidden"]) !!}

<div class="clearfix"></div>

<!-- 'Boolean Is Banned Field' checked by default -->
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="is_banned_div">
    {!! Form::label('is_banned', 'Ban Client:', ['data-test'=>"is_banned_label"]) !!}
    <div class="input-group" data-test="is_banned_group">
        <span class="input-group-addon">
            {!! Form::hidden('is_banned', 0) !!}
            @if(empty($client) || $client->is_banned==0 || !$client->is_banned)
                @php $checked = ''; @endphp
            @else
                @php $checked = true; @endphp
            @endif
            {{ Form::checkbox('is_banned', '1', $checked, ['id'=>"is_banned_field", 'data-test'=>"is_banned_field"])  }}
         </span>
         <input type="text" class="form-control" aria-label="..." value="Is Banned?">
    </div><!-- /.col-lg-6 -->
</div>


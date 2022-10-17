<!-- User Id Field -->
@if( !empty($mapValue) && !empty($mapValue->user_id))

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="user_id_div">
        {!! Form::label('user_id', 'Created By:', ['data-test'=>"user_id_label"]) !!}
        {!! Form::text('', $mapValue->user->email, ['readonly'=>'readonly', 'class' => 'form-control', 'id'=>"user_id_field", 'data-test'=>"user_id_field"]) !!}
    </div>

    <div class="clearfix"></div>

@endif

<!-- Slug Field -->
@if( !empty($mapValue) && !empty($mapValue->slug) )

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="slug_div">
        {!! Form::label('slug', 'Slug:', ['data-test'=>"slug_label"]) !!}
        {!! Form::text('slug', null, ['class' => 'form-control', 'id'=>"slug_field", 'readonly'=>'readonly', LemurEngine\LemurBot\Models\mapValue::getFormValidation('slug'), 'data-test'=>"slug_field"]) !!}
    </div>

    <div class="clearfix"></div>

@endif


<!-- Map Id Field -->
@if( !empty($mapValue) && !empty($mapValue->map_id))

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="map_id_div">
        {!! Form::label('map_id', 'Map Id:', ['data-test'=>"map_id_label"]) !!}
        <div class="input-group">
            {!! Form::hidden('map_id', $mapValue->map_id, ['id'=>"map_id_hidden_field", 'data-test'=>"map_id_hidden_field", LemurEngine\LemurBot\Models\MapValue::getFormValidation('map_id')]) !!}
            {!! Form::text('map', $mapValue->map->name, ['readonly'=>'readonly','class' => 'form-control', 'id'=>"map_id_field", 'data-test'=>"map_id_field", LemurEngine\LemurBot\Models\MapValue::getFormValidation('map_id')]) !!}
            <div class="input-group-btn">
                @if(!empty($mapValue)&&!empty($mapValue->map_id))
                    <a href="{!!url('/maps/'.$mapValue->map->slug) !!}" id='mapValue_button' class='btn btn-warning' data-test='mapValue_button'><i class='fa fa-arrow-right'></i></a>
                @endif
            </div>
        </div>
    </div>

    <div class="clearfix"></div>

@else

    <!-- Map Id Field -->
    <div class="form-group col-lg-6 col-md-6 col-sm-12 select2" data-test="map_id_div">
        {!! Form::label('map_id', 'Map:', ['data-test'=>"map_id_label"]) !!}
        {!! Form::select('map_id', $mapList, null, [  'placeholder'=>'Please Select', 'class' => 'form-control select2 generic', 'data-test'=>"$htmlTag-map_id_field", 'id'=>"$htmlTag-map_id_field"]) !!}
    </div>

    <div class="clearfix"></div>

@endif

<!-- Word Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="word_div">
    {!! Form::label('word', 'Word:', ['data-test'=>"word_label"]) !!}
    {!! Form::text('word', null, ['class' => 'form-control', LemurEngine\LemurBot\Models\mapValue::getFormValidation('word'),'id'=>"word_field", 'data-test'=>"word_field"] ) !!}
</div>

<div class="clearfix"></div>



<!-- Value Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="value_div">
    {!! Form::label('value', 'Value:', ['data-test'=>"value_label"]) !!}
    {!! Form::text('value', null, ['class' => 'form-control', LemurEngine\LemurBot\Models\mapValue::getFormValidation('value'),'id'=>"value_field", 'data-test'=>"value_field"] ) !!}
</div>

<div class="clearfix"></div>

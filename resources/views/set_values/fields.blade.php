<!-- User Id Field -->
@if( !empty($setValue) && !empty($setValue->user_id))

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="user_id_div">
        {!! Form::label('user_id', 'Created By:', ['data-test'=>"user_id_label"]) !!}
        {!! Form::text('', $setValue->user->email, ['readonly'=>'readonly', 'class' => 'form-control', 'id'=>"user_id_field", 'data-test'=>"user_id_field"]) !!}
    </div>

    <div class="clearfix"></div>

@endif

<!-- Slug Field -->
@if( !empty($setValue) && !empty($setValue->slug) )

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="slug_div">
        {!! Form::label('slug', 'Slug:', ['data-test'=>"slug_label"]) !!}
        {!! Form::text('slug', null, ['class' => 'form-control', 'id'=>"slug_field", 'readonly'=>'readonly', LemurEngine\LemurBot\Models\SetValue::getFormValidation('slug'), 'data-test'=>"slug_field"]) !!}
    </div>

    <div class="clearfix"></div>

@endif

<!-- Set Id Field -->
@if( !empty($setValue) && !empty($setValue->set_id))

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="set_id_div">
        {!! Form::label('set_id', 'Set Id:', ['data-test'=>"set_id_label"]) !!}
        <div class="input-group">
            {!! Form::hidden('set_id', $setValue->set_id, ['id'=>"set_id_hidden_field", 'data-test'=>"set_id_hidden_field", LemurEngine\LemurBot\Models\SetValue::getFormValidation('set_id')]) !!}
            {!! Form::text('set', $setValue->set->name, ['readonly'=>'readonly','class' => 'form-control', 'id'=>"set_id_field", 'data-test'=>"set_id_field", LemurEngine\LemurBot\Models\SetValue::getFormValidation('set_id')]) !!}
            <div class="input-group-btn">
                @if(!empty($setValue)&&!empty($setValue->set_id))
                    <a href="{!!url('/sets/'.$setValue->set->slug) !!}" id='setValue_button' class='btn btn-warning' data-test='setValue_button'><i class='fa fa-arrow-right'></i></a>
                @endif
            </div>
        </div>
    </div>

    <div class="clearfix"></div>

@else

    <!-- Set Id Field -->
    <div class="form-group col-lg-6 col-md-6 col-sm-12 select2" data-test="set_id_div">
        {!! Form::label('set_id', 'Set:', ['data-test'=>"set_id_label"]) !!}
        {!! Form::select('set_id', $setList, null, [  'placeholder'=>'Please Select', 'class' => 'form-control select2 generic', 'data-test'=>"$htmlTag-set_id_field", 'id'=>"$htmlTag-set_id_field"]) !!}
    </div>

    <div class="clearfix"></div>

@endif





<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="value_div">
    {!! Form::label('value', 'Value:', ['data-test'=>"value_label"]) !!}
    {!! Form::text('value', null, ['class' => 'form-control', LemurEngine\LemurBot\Models\SetValue::getFormValidation('value'),'id'=>"value_field", 'data-test'=>"value_field"] ) !!}
</div>

<div class="clearfix"></div>

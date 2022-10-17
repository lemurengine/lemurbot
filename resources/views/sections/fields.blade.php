<!-- User Id Field -->
@if( !empty($section) && !empty($section->user_id))

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="user_id_div">
        {!! Form::label('user_id', 'Created By:', ['data-test'=>"user_id_label"]) !!}
        {!! Form::text('', $section->user->email, ['readonly'=>'readonly', 'class' => 'form-control', 'id'=>"user_id_field", 'data-test'=>"user_id_field"]) !!}
    </div>
    <div class="clearfix"></div>

@endif

<!-- Slug Field -->
@if( !empty($section) && !empty($section->slug) )

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="slug_div">
        {!! Form::label('slug', 'Slug:', ['data-test'=>"slug_label"]) !!}
        {!! Form::text('slug', null, ['class' => 'form-control', 'id'=>"slug_field", 'readonly'=>'readonly', LemurEngine\LemurBot\Models\section::getFormValidation('slug'), 'data-test'=>"slug_field"]) !!}
    </div>
    <div class="clearfix"></div>

@endif


<!-- Name Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="name_div">
    {!! Form::label('name', 'Name:', ['data-test'=>"name_label"]) !!}
    {!! Form::text('name', null, ['class' => 'form-control', LemurEngine\LemurBot\Models\section::getFormValidation('name'),'id'=>"name_field", 'data-test'=>"name_field"] ) !!}
</div>
<div class="clearfix"></div>

<!-- Order Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="order_div">
    {!! Form::label('order', 'Display Order:', ['data-test'=>"order_label"]) !!}
    {!! Form::number('order', null, ['rows' => 2, 'class' => 'form-control', 'id'=>"order_field", 'data-test'=>"order_field", LemurEngine\LemurBot\Models\Section::getFormValidation('order')] ) !!}
</div>

<div class="clearfix"></div>

<!-- Status Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12 select2" data-test="status_div">
    {!! Form::label('type', 'Type:', ['data-test'=>"type_label"]) !!}
    {!! Form::select('type', config('lemurbot.dropdown.sections'), null, [ 'class' => 'form-control select2 first-option', LemurEngine\LemurBot\Models\Section::getFormValidation('type'), 'data-test'=>"$htmlTag-type-select", 'id'=>"$htmlTag-type-select"]) !!}
</div>
<div class="clearfix"></div>
<!-- Default State Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12 select2" data-test="section_state_div">
    {!! Form::label('default_state', 'Default State:', ['data-test'=>"default_status_label"]) !!}
    {!! Form::select('default_state', config('lemurbot.dropdown.default_state'), null, [ 'class' => 'form-control select2 first-option', LemurEngine\LemurBot\Models\Section::getFormValidation('default_state'), 'data-test'=>"$htmlTag-default-state-select", 'id'=>"$htmlTag-default-state-select"]) !!}
</div>

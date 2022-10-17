<!-- Slug Field -->
@if( !empty($normalization) && !empty($normalization->slug) )

    <div class="form-group col-sm-12 col-md-6 col-lg-6" data-test="slug_div">
        {!! Form::label('slug', 'Slug:', ['data-test'=>"slug_label"]) !!}
        {!! Form::text('slug', null, ['class' => 'form-control', 'id'=>"slug_field", 'readonly'=>'readonly', LemurEngine\LemurBot\Models\normalization::getFormValidation('slug'), 'data-test'=>"slug_field"]) !!}
    </div>

@endif

<div class="clearfix"></div>

<!-- Language Id Field -->
<div class="form-group col-sm-12 col-md-6 col-lg-6 select2" data-test="language_id_div">
    {!! Form::label('language_id', 'Language:', ['data-test'=>"language_id_label"]) !!}
    {!! Form::select('language_id', $languageList, (!empty($normalization)?$normalization->language->slug:(!empty($normalization)?$normalization->language->slug:"")), [  'placeholder'=>'Please Select', 'class' => 'form-control select2 generic', 'data-test'=>"$htmlTag-language_id-select", 'id'=>"$htmlTag-language_id-select"]) !!}
</div>

<div class="clearfix"></div>

<!-- Original Value Field -->
<div class="form-group col-sm-12 col-md-6 col-lg-6" data-test="original_value_div">
    {!! Form::label('original_value', 'Original Value:', ['data-test'=>"original_value_label"]) !!}
    {!! Form::text('original_value', null, ['class' => 'form-control', LemurEngine\LemurBot\Models\normalization::getFormValidation('original_value'),'id'=>"original_value_field", 'data-test'=>"original_value_field"] ) !!}
</div>

<div class="clearfix"></div>

<!-- Normalized Value Field -->
<div class="form-group col-sm-col-sm-12 col-md-6 col-lg-6" data-test="normalized_value_div">
    {!! Form::label('normalized_value', 'Normalized Value:', ['data-test'=>"normalized_value_label"]) !!}
    {!! Form::text('normalized_value', null, ['class' => 'form-control', LemurEngine\LemurBot\Models\normalization::getFormValidation('normalized_value'),'id'=>"normalized_value_field", 'data-test'=>"normalized_value_field"] ) !!}
</div>







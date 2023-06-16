<!-- User Id Field -->
@if( !empty($categoryGroup) && !empty($categoryGroup->user_id))

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="user_id_div">
        {!! Form::label('user_id', 'Created By:', ['data-test'=>"user_id_label"]) !!}
        {!! Form::text('', $categoryGroup->user->email, ['readonly'=>'readonly', 'class' => 'form-control', 'id'=>"user_id_field", 'data-test'=>"user_id_field"]) !!}
    </div>

    <div class="clearfix"></div>

@endif

<!-- Slug Field -->
@if( !empty($categoryGroup) && !empty($categoryGroup->slug) )

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="slug_div">
        {!! Form::label('slug', 'Slug:', ['data-test'=>"slug_label"]) !!}
        {!! Form::text('slug', null, ['class' => 'form-control', 'id'=>"slug_field", 'readonly'=>'readonly', LemurEngine\LemurBot\Models\CategoryGroup::getFormValidation('slug'), 'data-test'=>"slug_field"]) !!}
    </div>

    <div class="clearfix"></div>

@endif

<!-- Language Id Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12 select2" data-test="language_id_div">
    {!! Form::label('language_id', 'Language:', ['data-test'=>"language_id_label"]) !!}
    {!! Form::select('language_id', $languageList, (!empty($categoryGroup)?$categoryGroup->language->slug:(!empty($categoryGroup)?$categoryGroup->language->slug:"")), [  'placeholder'=>'Please Select', 'class' => 'form-control select2 generic', 'data-test'=>"$htmlTag-language_id-select", 'id'=>"$htmlTag-language_id-select"]) !!}
</div>

<div class="clearfix"></div>


<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="name_div">
    {!! Form::label('name', 'Name:', ['data-test'=>"name_label"]) !!}
    {!! Form::text('name', null, ['class' => 'form-control', LemurEngine\LemurBot\Models\CategoryGroup::getFormValidation('name'),'id'=>"name_field", 'data-test'=>"name_field"] ) !!}
</div>

<div class="clearfix"></div>


<!-- Description Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="description_div">
    {!! Form::label('description', 'Description:', ['data-test'=>"description_label"]) !!}
    {!! Form::textarea('description', null, ['rows' => 2, 'class' => 'form-control', 'id'=>"description_field", 'data-test'=>"description_field", LemurEngine\LemurBot\Models\CategoryGroup::getFormValidation('description')] ) !!}
</div>

<div class="clearfix"></div>

<!-- Status Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12 select2" data-test="status_div">
    {!! Form::label('status', 'Status:', ['data-test'=>"status_label"]) !!}
    {!! Form::select('status', config('lemurbot.dropdown.item_status'), null, [  'class' => 'form-control select2 first-option', LemurEngine\LemurBot\Models\CategoryGroup::getFormValidation('status'), 'data-test'=>"$htmlTag-status-select", 'id'=>"$htmlTag-status-select"]) !!}
</div>

<div class="clearfix"></div>


<!-- Section Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12 select2" data-test="section_div">
    {!! Form::label('section_id', 'Section:', ['data-test'=>"section_id_label"]) !!}
    {!! Form::select('section_id', $categoryGroupSectionList, (!empty($categoryGroup)&&!empty($categoryGroup->section)?$categoryGroup->section->slug:(!empty($categoryGroup)&&!empty($categoryGroup->section)?$categoryGroup->section->slug:"")), [  'placeholder'=>'Please Select','class' => 'form-control select2 first-option', LemurEngine\LemurBot\Models\CategoryGroup::getFormValidation('section_id'), 'data-test'=>"$htmlTag-section-id-select", 'id'=>"$htmlTag-section-id-select"]) !!}
</div>

<div class="clearfix"></div>

@if(LemurPriv::isAdmin(Auth::user()))

    <!-- Is Master Field -->
    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="is_master_div">
        {!! Form::label('is_master', 'Is Master:', ['data-test'=>"is_master_label"]) !!}
        <div class="input-group" data-test="is_master_group">
            <span class="input-group-addon">
                {!! Form::hidden('is_master', 0) !!}
                @if(empty($categoryGroup) || $categoryGroup->is_master==0 || !$categoryGroup->is_master)
                    @php $checked = ''; @endphp
                @else
                    @php $checked = true; @endphp
                @endif
                {{ Form::checkbox('is_master', '1', $checked, ['id'=>"is_master_field", 'data-test'=>"is_master_field"])  }}
             </span>
             <input type="text" class="form-control" aria-label="..." value="Is Master?">
        </div><!-- /.col-lg-6 -->
    </div>

<div class="clearfix"></div>

@endif

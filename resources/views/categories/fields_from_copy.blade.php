

<!-- Category Group Slug Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12 select2" data-test="category_group_id_div">
    {!! Form::label('category_group_slug', 'Category Group:', ['data-test'=>"category_group_slug_label"]) !!}
    {!! Form::select('category_group_slug', $categoryGroupList, (!empty($category)&(!empty($category->categoryGroup))?$category->categoryGroup->slug:'user-defined-'.Auth::user()->slug), [  LemurEngine\LemurBot\Models\WordSpelling::getFormValidation('category_group_id'), 'placeholder'=>'Please Select', 'class' => 'form-control allow-new select2', 'data-test'=>"$htmlTag-category_group_slug_select", 'id'=>"$htmlTag-category_group_slug_select-select"]) !!}
    <small class="help-block" data-test="help-block-select-default-file">
        <span>It is advisable that you save your custom AIML to your personalised 'user-defined-{!! Auth::user()->slug !!}' category group.</span>
    </small>
</div>

<div class="clearfix"></div>

<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="pattern_div">
    {!! Form::label('pattern', 'Pattern:', ['data-test'=>"pattern_label"]) !!}
    {!! Form::text('pattern', $category->pattern, ['class' => 'form-control', LemurEngine\LemurBot\Models\Category::getFormValidation('pattern'),'id'=>"pattern_field", 'data-test'=>"pattern_field"] ) !!}
    <small class="help-block" data-test="help-block-pattern-field">
        <span>You do not need to add the enclosing &lt;pattern>&lt;/pattern> tags.<br/>All fields will be normalized when you save.</span>
    </small>
</div>

<div class="clearfix"></div>

<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="topic_div">
    {!! Form::label('topic', 'Topic:', ['data-test'=>"topic_label"]) !!}

    @if(!empty($category->topic))
        <div class="input-group">
            {!! Form::text('topic', $category->topic, ['class' => 'form-control', LemurEngine\LemurBot\Models\Category::getFormValidation('topic'),'id'=>"topic_field", 'data-test'=>"topic_field"] ) !!}

            <div class="input-group-btn">
                <span name='clear' id='clear-button' data-field="topic_field" data-test='clear-button' class='btn btn-warning clear-button'><i class='fa fa-remove'></i> clear</span>
            </div>
        </div>
    @else
        {!! Form::text('topic', null, ['class' => 'form-control', LemurEngine\LemurBot\Models\Category::getFormValidation('topic'),'id'=>"topic_field", 'data-test'=>"topic_field"] ) !!}

    @endif

  </div>

<div class="clearfix"></div>

<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="that_div">
    {!! Form::label('that', 'That:', ['data-test'=>"that_label"]) !!}

    @if(!empty($category->that))
        <div class="input-group">
            {!! Form::text('that', $category->that, ['class' => 'form-control', LemurEngine\LemurBot\Models\Category::getFormValidation('that'),'id'=>"that_field", 'data-test'=>"that_field"] ) !!}
            <div class="input-group-btn">
                <span name='clear' id='clear-button' data-field="that_field" data-test='clear-button' class='btn btn-warning clear-button'><i class='fa fa-remove'></i> clear</span>
            </div>
        </div>
    @else
        {!! Form::text('that', null, ['class' => 'form-control', LemurEngine\LemurBot\Models\Category::getFormValidation('that'),'id'=>"that_field", 'data-test'=>"that_field"] ) !!}

    @endif

    <small class="help-block" data-test="help-block-that-field">
        <span>You do not need to add the enclosing &lt;that>&lt;/that> tags.</span>
    </small>
</div>

<div class="clearfix"></div>

<!-- Template Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="template_div">
    {!! Form::label('template', 'Template:', ['data-test'=>"template_label"]) !!}
    {!! Form::textarea('template', $category->template, ['rows' => 10, 'class' => 'form-control', 'id'=>"template_field", 'data-test'=>"template_field", LemurEngine\LemurBot\Models\Category::getFormValidation('template')] ) !!}
    <small class="help-block" data-test="help-block-template-field">
        <span>You do not need to add the enclosing &lt;template>&lt;/template> tags.<br/>All fields will be normalized when you save.</span>
    </small>
</div>

<div class="clearfix"></div>

<!-- Status Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12 select2" data-test="status_div">
    {!! Form::label('status', 'Status:', ['data-test'=>"status_label"]) !!}
    {!! Form::select('status', config('lemurbot.dropdown.item_status'), null, [  'class' => 'form-control select2 first-option', LemurEngine\LemurBot\Models\Category::getFormValidation('status'), 'data-test'=>"$htmlTag-status-select", 'id'=>"$htmlTag-status-select"]) !!}
</div>


<div class="clearfix"></div>


{!! Form::hidden('redirect_url', url('/categories')) !!}


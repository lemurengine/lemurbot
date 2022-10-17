<!-- User Id Field -->
@if( !empty($botCategoryGroup) && !empty($botCategoryGroup->user_id))

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="user_id_div">
        {!! Form::label('user_id', 'Created By:', ['data-test'=>"user_id_label"]) !!}
        {!! Form::text('', $botCategoryGroup->user->email, ['readonly'=>'readonly', 'class' => 'form-control', 'id'=>"user_id_field", 'data-test'=>"user_id_field"]) !!}
    </div>
    <div class="clearfix"></div>
@endif

@if(!empty($botCategoryGroup) && !empty($botCategoryGroup->bot))
    <!-- Bot Id Field -->
    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="category_group_id_div">
        {!! Form::label('bot_id', 'Bot:', ['data-test'=>"bot_id_label"]) !!}
        <div class="input-group">
            {!! Form::text('bot_id', $botCategoryGroup->bot->name, ['readonly'=>'readonly','class' => 'form-control', 'id'=>"bot_id_field", 'data-test'=>"bot_id_field", LemurEngine\LemurBot\Models\SetValue::getFormValidation('bot_id')]) !!}
            <div class="input-group-btn">
                @if(!empty($botCategoryGroup)&&!empty($botCategoryGroup->bot_id))
                    <a href="{!!url('/bots/'.$botCategoryGroup->bot->slug) !!}" id='bot_button' class='btn btn-warning' data-test='bot_button'><i class='fa fa-arrow-right'></i></a>
                @endif
            </div>
        </div>
    </div>

    <div class="clearfix"></div>

@else

    <!-- Bot Id Field -->
    <div class="form-group col-lg-6 col-md-6 col-sm-12 select2" data-test="bot_id_div">
        {!! Form::label('bot_id', 'Bot:', ['data-test'=>"bot_id_label"]) !!}
        {!! Form::select('bot_id', $botList, (!empty($botCategoryGroup)?$botCategoryGroup->bot->slug:(!empty($botCategoryGroup)?$botCategoryGroup->bot->slug:"")), [  'placeholder'=>'Please Select', 'class' => 'form-control select2 generic', LemurEngine\LemurBot\Models\BotCategoryGroup::getFormValidation('bot_id'), 'data-test'=>"$htmlTag-bot_id-select", 'id'=>"$htmlTag-bot_id-select"]) !!}
    </div>

    <div class="clearfix"></div>

@endif



<div class="clearfix"></div>

@if(!empty($botCategoryGroup) && !empty($botCategoryGroup->categoryGroup))

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="category_group_id_div">
        {!! Form::label('category_group_id', 'Category Group:', ['data-test'=>"category_group_id_label"]) !!}
        <div class="input-group">
            {!! Form::text('category_group_id', $botCategoryGroup->categoryGroup->name, ['readonly'=>'readonly','class' => 'form-control', 'id'=>"category_group_id_field", 'data-test'=>"category_group_id_field", LemurEngine\LemurBot\Models\SetValue::getFormValidation('category_group_id')]) !!}
            <div class="input-group-btn">
                @if(!empty($botCategoryGroup)&&!empty($botCategoryGroup->category_group_id))
                    <a href="{!!url('/categoryGroups/'.$botCategoryGroup->categoryGroup->slug) !!}" id='categoryGroup_button' class='btn btn-warning' data-test='categoryGroup_button'><i class='fa fa-arrow-right'></i></a>
                @endif
            </div>
        </div>
    </div>

    <div class="clearfix"></div>

@else

    <!-- Category Group Id Field -->
    <div class="form-group col-lg-6 col-md-6 col-sm-12 select2" data-test="category_group_id_div">
        {!! Form::label('category_group_id', 'Category Group:', ['data-test'=>"category_group_id_label"]) !!}
        {!! Form::select('category_group_id', $categoryGroupList, (!empty($botCategoryGroup)?$botCategoryGroup->categoryGroup->slug:(!empty($botCategoryGroup)?$botCategoryGroup->categoryGroup->slug:"")), [  LemurEngine\LemurBot\Models\WordSpelling::getFormValidation('category_group_id'), 'placeholder'=>'Please Select', 'class' => 'form-control select2 generic', 'data-test'=>"$htmlTag-category_group_id_select", 'id'=>"$htmlTag-category_group_id_select-select"]) !!}
    </div>

    <div class="clearfix"></div>

@endif

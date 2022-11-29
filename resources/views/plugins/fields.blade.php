<!-- User Id Field -->
@if( !empty($plugin) && !empty($plugin->user_id))

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="user_id_div">
        {!! Form::label('user_id', 'Created By:', ['data-test'=>"user_id_label"]) !!}
        {!! Form::text('', $plugin->user->email, ['readonly'=>'readonly', 'class' => 'form-control', 'id'=>"user_id_field", 'data-test'=>"user_id_field"]) !!}
    </div>

@endif

<!-- Slug Field -->
@if( !empty($plugin) && !empty($plugin->slug) )
    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="slug_div">
        {!! Form::label('slug', 'Slug:', ['data-test'=>"slug_label"]) !!}
        {!! Form::text('slug', null, ['class' => 'form-control', 'id'=>"slug_field", 'readonly'=>'readonly', LemurEngine\LemurBot\Models\Plugin::getFormValidation('slug'), 'data-test'=>"slug_field"]) !!}
    </div>
@endif
<div class="clearfix"></div>

<!-- Title Field -->
<div class="form-group col-sm-4" data-test="title_div">
    {!! Form::label('title', 'Title:', ['data-test'=>"title_label"]) !!}
    {!! Form::text('title', null, [ 'placeholder'=>'News Reader','maxlength' => 255,'maxlength' => 255, 'class' => 'form-control', LemurEngine\LemurBot\Models\Plugin::getFormValidation('title'), 'data-test'=>"$htmlTag-title-input", 'id'=>"$htmlTag-title-input"]) !!}
</div>

<!-- Description Field -->
<div class="form-group col-sm-8" data-test="description_div">
    {!! Form::label('description', 'Description:', ['data-test'=>"description_label"]) !!}
    {!! Form::text('description', null, [ 'placeholder'=>'This plugin reads news from news.org','maxlength' => 255,'maxlength' => 255, 'class' => 'form-control', LemurEngine\LemurBot\Models\Plugin::getFormValidation('description'), 'data-test'=>"$htmlTag-description-input", 'id'=>"$htmlTag-description-input"]) !!}
</div>

<div class="clearfix"></div>

<!-- Classname Field -->
<div class="form-group col-sm-8" data-test="classname_div">
    {!! Form::label('classname', 'Classname:', ['data-test'=>"classname_label"]) !!}
    {!! Form::text('classname', null, [ 'placeholder'=>'ReadFromNewsOrg','maxlength' => 255,'maxlength' => 255, 'class' => 'form-control', LemurEngine\LemurBot\Models\Plugin::getFormValidation('classname'), 'data-test'=>"$htmlTag-classname-input", 'id'=>"$htmlTag-classname-input"]) !!}
</div>

<!-- Apply Plugin Field -->
<div class="form-group col-lg-4 col-md-4 col-sm-4 select2" data-test="apply_plugin_div">
    {!! Form::label('apply_plugin', 'Apply Plugin:', ['data-test'=>"apply_plugin_label"]) !!}
    {!! Form::select('apply_plugin', ['pre'=>'Before AIML processing', 'post'=>'After AIML processing'], null, [  'placeholder'=>'Please Select', 'class' => 'form-control select2 generic', LemurEngine\LemurBot\Models\Plugin::getFormValidation('apply_plugin'), 'data-test'=>"$htmlTag-apply_plugin-select", 'id'=>"$htmlTag-apply_plugin-select"]) !!}
</div>

<!-- Return Onchange Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="return_onchange_div">
    {!! Form::label('return_onchange', 'Return Onchange:', ['data-test'=>"return_onchange_label"]) !!}
    <div class="input-group" data-test="return_onchange_group">
            <span class="input-group-addon">
                {!! Form::hidden('return_onchange', 0) !!}
                @if(empty($plugin) || $plugin->return_onchange==0 || !$plugin->return_onchange)
                    @php $checked = ''; @endphp
                @else
                    @php $checked = true; @endphp
                @endif
                {{ Form::checkbox('return_onchange', '1', $checked, ['id'=>"return_onchange_field", 'data-test'=>"return_onchange_field"])  }}
             </span>
        <input type="text" class="form-control" aria-label="..." value="Return Onchange?">
    </div><!-- /.col-lg-6 -->
</div>


@if(LemurPriv::isAdmin(Auth::user()))
    <!-- Is Master Field -->
    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="is_master_div">
        {!! Form::label('is_master', 'Is Master:', ['data-test'=>"is_master_label"]) !!}
        <div class="input-group" data-test="is_master_group">
            <span class="input-group-addon">
                {!! Form::hidden('is_master', 0) !!}
                @if(empty($plugin) || $plugin->is_master==0 || !$plugin->is_master)
                    @php $checked = ''; @endphp
                @else
                    @php $checked = true; @endphp
                @endif
                {{ Form::checkbox('is_master', '1', $checked, ['id'=>"is_master_field", 'data-test'=>"is_master_field"])  }}
             </span>
            <input type="text" class="form-control" aria-label="..." value="Is Master?">
        </div><!-- /.col-lg-6 -->
    </div>
@endif
<div class="clearfix"></div>

<!-- User Id Field -->
@if( !empty($map) && !empty($map->user_id))

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="user_id_div">
        {!! Form::label('user_id', 'Created By:', ['data-test'=>"user_id_label"]) !!}
        {!! Form::text('', $map->user->email, ['readonly'=>'readonly', 'class' => 'form-control', 'id'=>"user_id_field", 'data-test'=>"user_id_field"]) !!}
    </div>
    <div class="clearfix"></div>

@endif


<!-- Slug Field -->
@if( !empty($map) && !empty($map->slug) )

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="slug_div">
        {!! Form::label('slug', 'Slug:', ['data-test'=>"slug_label"]) !!}
        {!! Form::text('slug', null, ['class' => 'form-control', 'id'=>"slug_field", 'readonly'=>'readonly', LemurEngine\LemurBot\Models\map::getFormValidation('slug'), 'data-test'=>"slug_field"]) !!}
    </div>
    <div class="clearfix"></div>

@endif


<!-- Name Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="name_div">
    {!! Form::label('name', 'Name:', ['data-test'=>"name_label"]) !!}
    {!! Form::text('name', null, ['class' => 'form-control', LemurEngine\LemurBot\Models\map::getFormValidation('name'),'id'=>"name_field", 'data-test'=>"name_field"] ) !!}
</div>
<div class="clearfix"></div>

<!-- Description Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="description_div">
    {!! Form::label('description', 'Description:', ['data-test'=>"description_label"]) !!}
    {!! Form::textarea('description', null, ['rows' => 2, 'class' => 'form-control', 'id'=>"description_field", 'data-test'=>"description_field", LemurEngine\LemurBot\Models\Map::getFormValidation('description')] ) !!}
</div>

<div class="clearfix"></div>

@if(LemurPriv::isAdmin(Auth::user()))

<!-- 'Boolean Is Master Field' checked by default -->
<div class="form-group col-lg-6 col-md-6 col-sm-12 !!}" data-test="is_master_div">
    {!! Form::label('is_master', 'Is Master:', ['data-test'=>"is_master_label"]) !!}
    <div class="input-group" data-test="is_master_group">
        <span class="input-group-addon">
            {!! Form::hidden('is_master', 0) !!}
            @if(empty($map) || $map->is_master==0 || !$map->is_master)
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

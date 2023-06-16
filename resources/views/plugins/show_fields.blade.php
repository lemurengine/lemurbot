<!-- User Id Field -->
<div class="form-group">
    {!! Form::label('user_id', 'User Id:') !!}
    <p>{{ $plugin->user_id }}</p>
</div>

<!-- Slug Field -->
<div class="form-group">
    {!! Form::label('slug', 'Slug:') !!}
    <p>{{ $plugin->slug }}</p>
</div>

<!-- Title Field -->
<div class="form-group">
    {!! Form::label('title', 'Title:') !!}
    <p>{{ $plugin->title }}</p>
</div>

<!-- Description Field -->
<div class="form-group">
    {!! Form::label('description', 'Description:') !!}
    <p>{{ $plugin->description }}</p>
</div>

<!-- Classname Field -->
<div class="form-group">
    {!! Form::label('classname', 'Classname:') !!}
    <p>{{ $plugin->classname }}</p>
</div>

<!-- Priority Field -->
<div class="form-group">
    {!! Form::label('priority', 'Priority:') !!}
    <p>{{ $plugin->priority }}</p>
</div>

<!-- Apply Plugin Field -->
<div class="form-group">
    {!! Form::label('apply_plugin', 'Apply Plugin:') !!}
    <p>{{ $plugin->apply_plugin }}</p>
</div>

<!-- Return Onchange Field -->
<div class="form-group">
    {!! Form::label('return_onchange', 'Return Onchange:') !!}
    <p>{{ $plugin->return_onchange }}</p>
</div>

<!-- Is Master Field -->
<div class="form-group">
    {!! Form::label('is_master', 'Is Master:') !!}
    <p>{{ $plugin->is_master }}</p>
</div>

<!-- Is Active Field -->
<div class="form-group">
    {!! Form::label('is_active', 'Is Active:') !!}
    <p>{{ $plugin->is_active }}</p>
</div>

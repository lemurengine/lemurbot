<!-- User Id Field -->
<div class="form-group">
    {!! Form::label('user_id', 'Created By:') !!}
    <p>{{ $categoryGroup->user->email }}</p>
</div>

<!-- Language Id Field -->
<div class="form-group">
    {!! Form::label('language_id', 'Language:') !!}
    <p>{{ $categoryGroup->language->name }}</p>
</div>

<!-- Slug Field -->
<div class="form-group">
    {!! Form::label('slug', 'Slug:') !!}
    <p>{{ $categoryGroup->slug }}</p>
</div>

<!-- Name Field -->
<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    <p>{{ $categoryGroup->name }}</p>
</div>

<!-- Description Field -->
<div class="form-group">
    {!! Form::label('description', 'Description:') !!}
    <p>{{ $categoryGroup->description }}</p>
</div>

<!-- Status Field -->
<div class="form-group">
    {!! Form::label('status', 'Status:') !!}
    <p>{{ $categoryGroup->status }}</p>
</div>

<!-- Is Master Field -->
<div class="form-group">
    {!! Form::label('is_master', 'Is Master:') !!}
    <p>{{ $categoryGroup->is_master }}</p>
</div>


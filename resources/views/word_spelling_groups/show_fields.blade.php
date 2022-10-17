<!-- Language Id Field -->
<div class="form-group">
    {!! Form::label('language_id', 'Language:') !!}
    <p>{{ $wordSpellingGroup->language->name }}</p>
</div>

<!-- User Id Field -->
<div class="form-group">
    {!! Form::label('user_id', 'User Id:') !!}
    <p>{{ $wordSpellingGroup->user_id }}</p>
</div>

<!-- Slug Field -->
<div class="form-group">
    {!! Form::label('slug', 'Slug:') !!}
    <p>{{ $wordSpellingGroup->slug }}</p>
</div>

<!-- Name Field -->
<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    <p>{{ $wordSpellingGroup->name }}</p>
</div>

<!-- Description Field -->
<div class="form-group">
    {!! Form::label('description', 'Description:') !!}
    <p>{{ $wordSpellingGroup->description }}</p>
</div>

<!-- Is Master Field -->
<div class="form-group">
    {!! Form::label('is_master', 'Is Master:') !!}
    <p>{{ $wordSpellingGroup->is_master }}</p>
</div>


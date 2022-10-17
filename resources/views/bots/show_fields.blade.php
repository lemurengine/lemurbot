<!-- Slug Field -->
<div class="form-group">
    {!! Form::label('slug', 'Slug:') !!}
    <p>{{ $bot->slug }}</p>
</div>

<!-- Language Id Field -->
<div class="form-group">
    {!! Form::label('language_id', 'Language:') !!}
    <p>{{ $bot->language->name }}</p>
</div>

<!-- User Id Field -->
<div class="form-group">
    {!! Form::label('user_id', 'Created By:') !!}
    <p>{{ $bot->user->email }}</p>
</div>

<!-- Name Field -->
<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    <p>{{ $bot->name }}</p>
</div>

<!-- Summary Field -->
<div class="form-group">
    {!! Form::label('summary', 'Summary:') !!}
    <p>{{ $bot->summary }}</p>
</div>

<!-- Description Field -->
<div class="form-group">
    {!! Form::label('description', 'Description:') !!}
    <p>{{ $bot->description }}</p>
</div>

<!-- Default Response Field -->
<div class="form-group">
    {!! Form::label('default_response', 'Default Response:') !!}
    <p>{{ $bot->default_response }}</p>
</div>

<!-- Lemurtar URL Field -->
<div class="form-group">
    {!! Form::label('lemurtar_url', 'Lemurtar URL:') !!}
    <p>{{ $bot->lemurtar_url }}</p>
</div>

<!-- Image Field -->
<div class="form-group">
    {!! Form::label('image', 'Image:') !!}
    <p>{{ $bot->image }}</p>
</div>

<!-- Status Field -->
<div class="form-group">
    {!! Form::label('status', 'Status:') !!}
    <p>{{ $bot->status }}</p>
</div>

<!-- Is Public Field -->
<div class="form-group">
    {!! Form::label('is_public', 'Is Public:') !!}
    <p>{{ $bot->is_public }}</p>
</div>

<!-- Map Id Field -->
<div class="form-group">
    {!! Form::label('map_id', 'Map Id:') !!}
    <p>{{ $mapValue->user->email }}</p>
</div>

<!-- User Id Field -->
<div class="form-group">
    {!! Form::label('user_id', 'Created By:') !!}
    <p>{{ $mapValue->user_id }}</p>
</div>

<!-- Slug Field -->
<div class="form-group">
    {!! Form::label('slug', 'Slug:') !!}
    <p>{{ $mapValue->slug }}</p>
</div>

<!-- Word Field -->
<div class="form-group">
    {!! Form::label('word', 'Word:') !!}
    <p>{{ $mapValue->word }}</p>
</div>

<!-- Value Field -->
<div class="form-group">
    {!! Form::label('value', 'Value:') !!}
    <p>{{ $mapValue->value }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated:') !!}
    <p>{{ $mapValue->updated_at }}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created:') !!}
    <p>{{ $mapValue->created_at }}</p>
</div>

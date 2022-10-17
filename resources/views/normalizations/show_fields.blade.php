<!-- Language Id Field -->
<div class="form-group">
    {!! Form::label('language_id', 'Language:') !!}
    <p>{{ $normalization->language->name }}</p>
</div>


<!-- Slug Field -->
<div class="form-group">
    {!! Form::label('slug', 'Slug:') !!}
    <p>{{ $normalization->slug }}</p>
</div>

<!-- Original Value Field -->
<div class="form-group">
    {!! Form::label('original_value', 'Original Value:') !!}
    <p>{{ $normalization->original_value }}</p>
</div>

<!-- Normalized Value Field -->
<div class="form-group">
    {!! Form::label('normalized_value', 'Normalized Value:') !!}
    <p>{{ $normalization->normalized_value }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated:') !!}
    <p>{{ $normalization->updated_at }}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created:') !!}
    <p>{{ $normalization->created_at }}</p>
</div>

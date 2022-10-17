<!-- Bot Id Field -->
<div class="form-group">
    {!! Form::label('bot_id', 'Bot Id:') !!}
    <p>{{ $emptyResponse->bot->slug }}</p>
</div>

<!-- Slug Field -->
<div class="form-group">
    {!! Form::label('slug', 'Slug:') !!}
    <p>{{ $emptyResponse->slug }}</p>
</div>

<!-- Raw Input Field -->
<div class="form-group">
    {!! Form::label('input', 'Input:') !!}
    <p>{{ $emptyResponse->input }}</p>
</div>

<!-- Raw Input Field -->
<div class="form-group">
    {!! Form::label('occurrences', 'Occurrences:') !!}
    <p>{{ $emptyResponse->occurrences }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated:') !!}
    <p>{{ $emptyResponse->updated_at }}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created:') !!}
    <p>{{ $emptyResponse->created_at }}</p>
</div>

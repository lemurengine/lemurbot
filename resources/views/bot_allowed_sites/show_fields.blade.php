<!-- Bot Id Field -->
<div class="form-group">
    {!! Form::label('bot_id', 'Bot Id:') !!}
    <p>{{ $botAllowedSite->bot_id }}</p>
</div>

<!-- User Id Field -->
<div class="form-group">
    {!! Form::label('user_id', 'User Id:') !!}
    <p>{{ $botAllowedSite->user_id }}</p>
</div>

<!-- Slug Field -->
<div class="form-group">
    {!! Form::label('slug', 'Slug:') !!}
    <p>{{ $botAllowedSite->slug }}</p>
</div>

<!-- Name Field -->
<div class="form-group">
    {!! Form::label('website_url', 'Website URL:') !!}
    <p>{{ $botAllowedSite->website_url }}</p>
</div>

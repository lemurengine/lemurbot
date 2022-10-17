<!-- Conversation Id Field -->
<div class="form-group">
    {!! Form::label('conversation_id', 'Conversation Id:') !!}
    <p>{{ $conversationSource->conversation->slug }}</p>
</div>

<!-- Slug Field -->
<div class="form-group">
    {!! Form::label('slug', 'Slug:') !!}
    <p>{{ $conversationSource->slug }}</p>
</div>

<!-- Params Field -->
<div class="form-group">
    {!! Form::label('params', 'Params:') !!}
    <pre>{!! json_encode(json_decode($conversationSource->params,true),JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) !!}</pre>
</div>

<!-- User Field -->
<div class="form-group">
    {!! Form::label('user', 'User:') !!}
    <p>{{ $conversationSource->user }}</p>
</div>

<!-- IP Field -->
<div class="form-group">
    {!! Form::label('ip', 'IP:') !!}
    <p>{{ $conversationSource->usiper }}</p>
</div>

<!-- User Agent Field -->
<div class="form-group">
    {!! Form::label('user_agent', 'User Agent:') !!}
    <p>{{ $conversationSource->user_agent }}</p>
</div>

<!-- Referer Field -->
<div class="form-group">
    {!! Form::label('referer', 'Referer:') !!}
    <p>{{ $conversationSource->referer }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated:') !!}
    <p>{{ $conversationSource->updated_at }}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created:') !!}
    <p>{{ $conversationSource->created_at }}</p>
</div>

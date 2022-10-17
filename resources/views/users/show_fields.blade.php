<!-- Slug Field -->
<div class="form-group">
    {!! Form::label('Id', 'Id:') !!}
    <p>{{ $user->slug }}</p>
</div>

<!-- Name Field -->
<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    <p>{{ $user->name }}</p>
</div>

<!-- Email Field -->
<div class="form-group">
    {!! Form::label('email', 'Email:') !!}
    <p>{{ $user->email }}</p>
</div>


<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated:') !!}
    <p>{{ $user->updated_at }}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created:') !!}
    <p>{{ $user->created_at }}</p>
</div>

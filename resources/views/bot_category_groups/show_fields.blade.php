<!-- User Id Field -->
<div class="form-group">
    {!! Form::label('user_id', 'Created By:') !!}
    <p>{{ $botCategoryGroup->user->email }}</p>
</div>

<!-- Bot Id Field -->
<div class="form-group">
    {!! Form::label('bot_id', 'Bot:') !!}
    <p>{{ $botCategoryGroup->bot->name }}</p>
</div>

<!-- Category Group Id Field -->
<div class="form-group">
    {!! Form::label('category_group_id', 'Category Group Id:') !!}
    <p>{{ $botCategoryGroup->category_group_id }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated:') !!}
    <p>{{ $botCategoryGroup->updated_at }}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created:') !!}
    <p>{{ $botCategoryGroup->created_at }}</p>
</div>

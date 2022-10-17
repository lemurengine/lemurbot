<div class='btn-group'  data-test="{$htmlTag}-datatable-actions">
    <a href="{{ route('categories.show', $id) }}" class='btn btn-info btn-xs show-button' data-test="show-button">
        <i class="glyphicon glyphicon-eye-open"></i>
    </a>
    <a href="{{ url('/category/fromCopy/'.$id) }}" class='btn btn-primary btn-xs show-button' data-test="show-button">
        <i class="fa fa-plus"></i>
    </a>
    @if(Auth::user()->id == $user_id || LemurPriv::isAdmin(Auth::user()))

        <a href="{{ route('categories.edit', $id) }}"  class='btn btn-success btn-xs edit-button' data-test="edit-button">
            <i class="glyphicon glyphicon-edit"></i>
        </a>
        <a class='btn btn-danger btn-xs delete-button openDeleteDataTableModal' data-id="{!! $id !!}"  data-message="ID: {!! $id !!}" data-test="delete-button">
            <i class="glyphicon glyphicon-trash"></i>
        </a>

    @endif

    {!! Form::hidden('rowId', $id, ['class'=>'rowId'] ) !!}
    {!! Form::hidden('actionId', 'edit', ['class'=>'actionId'] ) !!}
</div>

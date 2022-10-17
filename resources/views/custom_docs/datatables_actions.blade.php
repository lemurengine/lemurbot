<div class='btn-group' data-test="{!! $htmlTag !!}-datatable-actions">
    @if($deletedAt !== null)
        <a data-test="restore-modal-row-{{$id}}" data-id="{{$id}}" class="btn btn-warning btn-xs openRestoreModal"><i class="fa fa-undo"></i> </a>
        <a class='btn bg-black btn-xs hard-delete-button openHardDeleteDataTableModal' data-id="{!! $id !!}"  data-message="{!! $title !!} ID: {!! $id !!}" data-test="hard-delete-button">
            <i class="glyphicon glyphicon-trash"></i>
        </a>
        {!! Form::hidden('actionId', 'warning_restore', ['class'=>'actionId'] ) !!}
    @else
        <a href="{{ route($link.'.show', $id) }}" class='btn btn-info btn-xs show-button' data-test="show-button">
            <i class="glyphicon glyphicon-eye-open"></i>
        </a>
        <a href="{{ route($link.'.edit', $id) }}" class='btn btn-success btn-xs edit-button' data-test="edit-button">
            <i class="glyphicon glyphicon-edit"></i>
        </a>
        <a class='btn btn-danger btn-xs delete-button openDeleteDataTableModal' data-id="{!! $id !!}"  data-message="{!! $title !!} ID: {!! $id !!}" data-test="delete-button">
            <i class="glyphicon glyphicon-trash"></i>
        </a>
        {!! Form::hidden('actionId', 'edit', ['class'=>'actionId'] ) !!}
    @endif
    {!! Form::hidden('rowId', $id, ['class'=>'rowId'] ) !!}
</div>

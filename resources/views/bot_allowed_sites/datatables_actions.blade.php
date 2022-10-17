<div class='btn-group' data-test="{!! $htmlTag !!}-datatable-actions">
    <a href="{{ url('/bot/sites/'.$bot.'/list') }}" class='btn btn-warning btn-xs show-button' data-test="show-button">
        <i class="fa fa-smile-o"></i>
    </a>
    <a href="{{ route($link.'.show', $id) }}" class='btn btn-info btn-xs show-button' data-test="show-button">
        <i class="glyphicon glyphicon-eye-open"></i>
    </a>
    <a href="{{ route($link.'.edit', $id) }}" class='btn btn-success btn-xs edit-button' data-test="edit-button">
        <i class="glyphicon glyphicon-edit"></i>
    </a>
    <a class='btn btn-danger btn-xs delete-button openDeleteDataTableModal' data-id="{!! $id !!}"  data-message="{!! $title !!} ID: {!! $id !!}" data-test="delete-button">
        <i class="glyphicon glyphicon-trash"></i>
    </a>
    {!! Form::hidden('rowId', $id, ['class'=>'rowId'] ) !!}
    {!! Form::hidden('actionId', 'edit', ['class'=>'actionId'] ) !!}
</div>

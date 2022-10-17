<div class='btn-group' data-test="{!! $htmlTag !!}-datatable-actions">
    <a href="{{ url('bots/'.$bot) }}" class='btn btn-warning btn-xs jump-button' data-test="jump-button">
        <i class="fa fa-smile-o"></i>
    </a>
    @if(!$is_banned)
    <a class='btn btn-danger btn-xs ban-button openBanDataTableModal' data-id="{!! $id !!}"  data-message="{!! $title !!} ID: {!! $id !!}" data-test="ban-button">
        <i class="fa fa-ban"></i>
    </a>
    @else
        <a class='btn btn-success btn-xs unban-button openUnBanDataTableModal' data-id="{!! $id !!}"  data-message="{!! $title !!} ID: {!! $id !!}" data-test="unban-button">
            <i class="fa fa-ban"></i>
        </a>
    @endif
    <a href="{{ route($link.'.show', $id) }}" class='btn btn-info btn-xs show-button' data-test="show-button">
        <i class="glyphicon glyphicon-eye-open"></i>
    </a>
    <a href="{{ route($link.'.edit', $id) }}"  class='btn btn-success btn-xs edit-button' data-test="edit-button">
        <i class="glyphicon glyphicon-edit"></i>
    </a>

    <a class='btn btn-danger btn-xs delete-button openDeleteDataTableModal' data-id="{!! $id !!}"  data-message="{!! $title !!} ID: {!! $id !!}" data-test="delete-button">
        <i class="glyphicon glyphicon-trash"></i>
    </a>
    {!! Form::hidden('rowId', $id, ['class'=>'rowId'] ) !!}
    {!! Form::hidden('actionId', 'edit', ['class'=>'actionId'] ) !!}
</div>

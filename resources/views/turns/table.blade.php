@section('css')
    @include('lemurbot::layouts.datatables_css')
@endsection

{!! $dataTable->table(['width' => '100%', 'data-test'=>$htmlTag.'-datatable', 'class' => 'table table-striped table-bordered hover'],true) !!}

@push('scripts')
    @include('lemurbot::layouts.datatables_js')
    {!! $dataTable->scripts() !!}
    {{ Html::script('vendor/lemurbot/js/tables.js') }}
@endpush

@include('lemurbot::layouts.datatable_delete_modal')

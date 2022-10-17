@include('layouts.app')
@push('style')
{{ Html::style('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css') }}
{{ Html::style('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css') }}
{{ Html::style('https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css') }}
{{ Html::style('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css') }}
@endpush

@push('scripts')
    <!-- jQuery 3.1.1 -->
{{ Html::script('https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js') }}
{{ Html::script('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js') }}
{{ Html::script('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js') }}
{{ Html::script('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js') }}
{{ Html::script('https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js') }}
<!-- AdminLTE App -->
{{ Html::script('/vendor/lemurbot/js/adminlte.min.js') }}
{{ Html::script('//cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js') }}
{{ Html::script('//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js') }}
{{ Html::script('//cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js') }}
{{ Html::script('//cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js') }}
@endpush


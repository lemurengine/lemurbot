@if (Auth::guest())
@php
    header("Location: " . URL::to('/'), true, 302);
    exit();
@endphp
@endif
    <!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{!! config('app.name') !!} :: Admin @if(isset($title)) {!! $title !!}@endif</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.3/css/AdminLTE.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.3/css/skins/_all-skins.min.css">

    <!-- iCheck -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/skins/square/_all.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css">

    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">


    <link rel="stylesheet" href="{{ asset('/vendor/lemurbot/css/style.css') }}">

    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/x-icon">

    @yield('css')
</head>

<body class="">
@if (!Auth::guest())
    <div class="wrapper">



    <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            <div class="popup-content">

                <div class="box box-primary direct-chat direct-chat-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Chat with {{$bot->name}}</h3>

                        <button type="button" class="close" onclick="window.close();" aria-label="Close"  data-test="direct-chat-window-close-dashboard">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <!-- Conversations are loaded here -->
                        <div id="direct-chat-window" class="direct-chat-messages" data-test="direct-chat-window-responses-dashboard">

                        </div>
                        <!--/.direct-chat-messages-->


                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <form action="#" method="post" id="chat-form">
                            <div class="input-group">
                                @csrf
                                <input type="hidden" name="bot" id="bot" value="{{$bot->slug}}"  data-test="chat-bot-id-dashboard">
                                <input type="hidden" name="client" id="client" value="{!! Auth::user()->slug !!}" data-test="chat-client-id-dashboard">
                                <input type="text" name="message" id="message" placeholder="Type Message ..." class="form-control" data-test="chat-input-dashboard">
                                <span class="input-group-btn">
                                        <button type="submit" id="submit-button" class="btn btn-primary btn-flat" data-test="chat-button-send-dashboard">Send</button>
                                    </span>
                            </div>
                        </form>
                    </div>
                    <!-- /.box-footer-->
                </div>
            </div>


        </div>




    </div>
@endif

<!-- jQuery 3.1.1 -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<!-- AdminLTE App -->
<script src="/js/adminlte.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js"></script>
<script>

    $('#chat-form').submit(function(e){
        e.preventDefault();

        $( "#submit-button" ).prop( "disabled", true );

        let userHtml = '<!-- Message. Default to the left -->\n' +
            '        <div class="direct-chat-msg">\n' +
            '            <div class="direct-chat-info clearfix">\n' +
            '                <span class="direct-chat-name pull-left">{!! Auth::user()->name !!}</span>\n' +
            '                <span class="direct-chat-timestamp pull-right">{!! Carbon\Carbon::now() !!}</span>\n' +
            '            </div>\n' +
            '            <!-- /.direct-chat-info -->\n' +
            '            <img class="direct-chat-img" src="{!! url("/widgets/images/user.png") !!}" alt="Message User Image"><!-- /.direct-chat-img -->\n' +
            '            <div class="direct-chat-text">\n' +
            $('#message').val() +
            '            </div>\n' +
            '            <!-- /.direct-chat-text -->\n' +
            '        </div>\n' +
            '        <!-- /.direct-chat-msg -->'

        $('#direct-chat-window').append(userHtml);

        var settings = {
            "url": '/api/talk/bot',
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({"client":$('#client').val(),"bot":$('#bot').val(),"message":$('#message').val()}),
        };

        $.ajax(settings).done(function (response) {

            $('#message').val('');
            console.log(response)

            let botHtml = '<!-- Message to the right -->\n' +
                '        <div class="direct-chat-msg right">\n' +
                '            <div class="direct-chat-info clearfix">\n' +
                '                <span class="direct-chat-name pull-right">'+response.data.bot.id+'</span>\n' +
                '                <span class="direct-chat-timestamp pull-left">{!! Carbon\Carbon::now() !!}</span>\n' +
                '            </div>\n' +
                '            <!-- /.direct-chat-info -->\n' +
                '            <img class="direct-chat-img" src="'+response.data.bot.image+'" alt="Bot Image"><!-- /.direct-chat-img -->\n' +
                '            <div class="direct-chat-text">\n' +
                response.data.conversation.output                 +
                '            </div>\n' +
                '            <!-- /.direct-chat-text -->\n' +
                '        </div>\n' +
                '        <!-- /.direct-chat-msg -->';

            $('#direct-chat-window').append(botHtml);
            $("#direct-chat-window").scrollTop($("#direct-chat-window")[0].scrollHeight);
            $( "#message" ).focus();
            $( "#submit-button" ).prop( "disabled", false );

        });
    })

    var element = document.getElementById('direct-chat-messages');
    element.scrollTop = element.scrollHeight - element.clientHeight;
    $( "#message" ).focus();
</script>
@stack('scripts')

@yield('scripts')
</body>
</html>

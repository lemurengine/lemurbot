<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{!! config('app.name') !!}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="./widgets/popup/chat.css">
    <!-- Styles -->
    <style>
        html, body {
            background-color: #FEF3EF;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">
    @if (Route::has('login'))
        <div class="top-right links">
            @auth
                <a href="{{ url('/home') }}">Home</a>
            @else
                <a href="{{ route('login') }}">Login</a>
            @endauth
        </div>
    @endif

    <div class="content">


        <img alt="{!! config('app.name') !!} Image" src="{!! config('lemurbot.portal.homepage_image') !!}" width="250">

        <div class="title m-b-md">
            {!! config('app.name') !!}
        </div>



    </div>


    <!-- bot chat window -->
    <div id="chat-popup" data-botId="{!! getHomePageBot() !!}" data-clientId=""></div>
    <!--/bot chat window -->

</div>
<script src="//code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="./widgets/popup/chat.js"></script>
</body>

</html>

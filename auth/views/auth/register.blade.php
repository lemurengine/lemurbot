<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{!! config('app.name') !!} :: Registration Page</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.3/css/AdminLTE.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.3/css/skins/_all-skins.min.css">

    <!-- iCheck -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/skins/square/_all.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition register-page">
<div class="register-box">
    <div class="register-logo">
        <a href="{{ url('/home') }}"><b>{!! config('app.name') !!} </b>Portal</a>
    </div>

    <div class="register-box-body">
        <p class="login-box-msg">Register a new membership</p>

        <form method="post" action="{{ url('/register') }}" data-test="register-form">
            @csrf

            <div class="form-group has-feedback{{ $errors->has('name') ? ' has-error' : '' }}" data-test="register-name-has-error">
                <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Full Name" data-test="register-name-input">
                <span class="glyphicon glyphicon-user form-control-feedback" data-test="register-name-feedback"></span>

                @if ($errors->has('name'))
                    <span class="help-block" data-test="register-name-help-block"></span>
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}" data-test="register-email-has-error">
                <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email" data-test="register-email-input">
                <span class="glyphicon glyphicon-envelope form-control-feedback" data-test="register-email-feedback"></span>

                @if ($errors->has('email'))
                    <span class="help-block" data-test="register-email-help-block"></span>
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}" data-test="register-password-has-error">
                <input type="password" class="form-control" name="password" placeholder="Password" data-test="register-password-input">
                <span class="glyphicon glyphicon-lock form-control-feedback" data-test="register-password-feedback"></span>

                @if ($errors->has('password'))
                    <span class="help-block" data-test="register-password-help-block"></span>
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group has-feedback{{ $errors->has('password_confirmation') ? ' has-error' : '' }}" data-test="register-confirm-password-has-error">
                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password" data-test="register-confirm-password-input">
                <span class="glyphicon glyphicon-lock form-control-feedback" data-test="register-confirm-password-feedback"></span>

                @if ($errors->has('password_confirmation'))
                    <span class="help-block" data-test="register-confirm-password-help-block"></span>
                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                    </span>
                @endif
            </div>

            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox" data-test="register-i-agree-checkbox"> I agree to the <a href="#">terms</a>
                        </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" data-test="register-button" class="btn btn-primary btn-block btn-flat">Register</button>
                </div>
                <!-- /.col -->
            </div>
        </form>

        <a href="{{ url('/login') }}" class="text-center"  data-test="register-login-link">I already have a membership</a>
    </div>
    <!-- /.form-box -->
</div>
<!-- /.register-box -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<!-- AdminLTE App -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.3/js/adminlte.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/icheck.min.js"></script>

<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
</script>
</body>
</html>

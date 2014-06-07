<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>ParkCMS Login</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ url('assets/css/main.css') }}" rel="stylesheet">

    <!-- Custom styles for this template -->
    <style>

    </style>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="signin">

<div class="container">
    <form action="{{ url('login/auth') }}" method="post" class="col-md-4 col-md-offset-4" role="form">
        <h2 class="form-signin-heading">Anmeldung</h2>
        @if (Session::has('auth_error'))
        <div class="alert alert-danger">{{ trans('auth.'.Session::get('auth_error')) }}</div>
        @endif
        @if (Session::has('auth_msg'))
        <div class="alert alert-success">{{ trans('auth.'.Session::get('auth_msg')) }}</div>
        @endif
        <input type="text" name="username" class="form-control" placeholder="Benutzername" required autofocus>
        <input type="password" name="password" class="form-control" placeholder="Passwort" required>
        <label class="checkbox">
            <input type="checkbox" name="remember-me" value="remember-me"> Angemeldet bleiben
        </label>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Anmelden</button>
    </form>

</div> <!-- /container -->
</body>
</html>
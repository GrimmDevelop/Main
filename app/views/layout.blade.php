<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Grimmdatenbank</title>

    <link rel="stylesheet" href="{{ url('assets/css/main.css') }}" />

    @yield('head')

    <style type="text/css">

        .alert {
            margin-bottom: 0;
        }

    </style>
</head>
<body>
    <nav class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#mainnav">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{ url('') }}" class="brand">Grimmdatenbank</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="mainnav">
                <ul class="nav navbar-nav">
                    <li><a href="{{ url('search') }}">Suche</a></li>
                    <li class="divider"></li>
                    <li><a href="{{ url('admin') }}">Administration</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        @foreach(Session::get('messages', []) as $type => $message)
        <div class="row">
            <div class="col-md-8 col-md-offset-2 alert-{{ type }}">
                {{ message }}
            </div>
        </div>
        @endforeach
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                @yield('body')
            </div>
        </div>
    </div>

    @section('script')
    <script src="{{ url('assets/js/main.js') }}"></script>
    <script>
        $(function() {
            $('.toggle_popover').popover();
        });
    </script>
    @show
</body>
</html>
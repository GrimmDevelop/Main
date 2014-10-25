<!DOCTYPE html>
<html lang="en" ng-app="grimmApp">
<head>
    <meta charset="utf8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Grimmdatenbank</title>

    <link rel="stylesheet" href="{{ url('assets/css/main.css') }}" />
    @asset('css')

    @yield('head')

</head>
<body>
    <div class="loading-indicator" loading-indicator style="display: none;"><img src="{{ url('assets/img/loader.gif') }}"></div>

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
                    <li><a href="{{ url('search') }}">{{ trans('menu.search') }}</a></li>
                    <li><a href="{{ url('api') }}">{{ trans('menu.api') }}</a></li>
                    <li><a href="{{ url(getenv('impressum')) }}">{{ trans('menu.impressum') }}</a></li>
@if(Sentry::check())
                    <li><a href="{{ url('admin') }}">{{ trans('menu.admin') }}</a></li>
                    <li class="dropdown">
                        <a href="#" data-toogle="dropdown" class="dropdown-toggle"><span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('admin') }}#/files">{{ trans('admin_default.files_nav') }}</a></li>
                            <li><a href="{{ url('admin') }}#/letters">{{ trans('admin_default.letters_nav') }}</a></li>
                            <li><a href="{{ url('admin') }}#/locations">{{ trans('admin_default.locations_nav') }}</a></li>
                            <li><a href="{{ url('admin') }}#/persons">{{ trans('admin_default.persons_nav') }}</a></li>
                            <li><a href="{{ url('admin') }}#/users">{{ trans('admin_default.users_nav') }}</a></li>
                            <li class="nav-divider"></li>
                            <li><a href="{{ url('admin') }}#/import">{{ trans('admin_default.import_nav') }}</a></li>
                            <li><a href="{{ url('admin') }}#/assign">{{ trans('admin_default.assign_locations') }}</a></li>
                        </ul>
                    </li>
@endif
                </ul>
                <ul class="nav navbar-nav pull-right">
@if(Sentry::check())
                    <li><a href="{{ url('logout') }}">{{ trans('menu.logout') }}</a></li>
@else
                    <li><a href="{{ url('login') }}">{{ trans('menu.login') }}</a></li>
@endif
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        @foreach(Session::get('messages', []) as $type => $message)
        <div class="row">
            <div class="col-md-12 alert-{{ type }}">
                {{ message }}
            </div>
        </div>
        @endforeach

        @yield('body')
    </div>

@if(Sentry::check() && View::exists('admin.compiled'))
    <!-- Partials -->
    @include('admin.compiled')
@endif

    <script src="{{ url('assets/js/main.js') }}"></script>
    @asset('js')
    <script>

        angular.module('grimmApp').constant('BASE_URL', '{{ url() }}');

        $(function() {
            $('.toggle_popover').popover();
        });
    </script>
</body>
</html>
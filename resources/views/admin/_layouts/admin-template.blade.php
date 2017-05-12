@extends('_layouts.main-template')

@section('header-styles-scripts')
    @if ( Auth::guard('web')->user() )
        <script>
            window.user = {!! $user !!};
            window.permissions = {!! json_encode($permissions) !!};
            window.settings = {!! json_encode($settings) !!};
        </script>
        @yield('view-header-styles-scripts')
    @endif
@endsection

@section('body')
    <nav class="navbar navbar-toggleable-md navbar-inverse bg-primary fixed-top">
        <div class="container">
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="{{ route('guest.home') }}">{{ config('app.name') }}</a>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                </ul>

                <ul class="navbar-nav">
                    @if ( Auth::guard('web')->guest() )
                        <li class="nav-item @yield('active-login')"><a class="nav-link" href="{{ route('admin.auth.show_login') }}">Login</a></li>
                        <li class="nav-item @yield('active-registration')"><a class="nav-link" href="{{ route('admin.auth.show_registration') }}">Register</a></li>
                    @else
                        <li class="nav-item dropdown">
                            <a href="" id="dropdown01" class="nav-link dropdown-toggle active" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ Auth::guard('web')->user()->name }}
                            </a>

                            <div class="dropdown-menu" aria-labelledby="dropdown01">
                                <a class="dropdown-item" href="{{ route('settings.index') }}">
                                    <i class="fa fa-cogs" aria-hidden="true"></i> User Settings
                                </a>
                                <a class="dropdown-item" href="{{ route('admin.auth.get_logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fa fa-sign-out" aria-hidden="true"></i> Logout
                                </a>
                                <form id="logout-form" action="{{ route('admin.auth.post_logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
    <div class="main-content" style="margin-top: 80px;">
        @if ( Auth::guard('web')->guest() )
            @yield('content')
        @else
            <div class="container">
                <div class="row">
                    <div class="col-lg-3">
                        @include('admin._layouts.admin-nav')
                    </div>
                    <div class="col-lg-9">
                        @yield('content')
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
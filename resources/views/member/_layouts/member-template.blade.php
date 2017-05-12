@extends('_layouts.main-template')

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
                    @if ( Auth::guard('member')->guest() )
                        <li class="nav-item @yield('active-login')"><a class="nav-link" href="{{ route('member.auth.show_login') }}">Login</a></li>
                        <li class="nav-item @yield('active-registration')"><a class="nav-link" href="{{ route('member.auth.show_registration') }}">Register</a></li>
                    @else
                        <li class="nav-item dropdown">
                            <a href="" id="dropdown01" class="nav-link dropdown-toggle active" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ Auth::guard('member')->user()->name }}
                            </a>

                            <div class="dropdown-menu" aria-labelledby="dropdown01">
                                <a class="dropdown-item" href="{{ route('member.auth.get_logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fa fa-sign-out" aria-hidden="true"></i> Logout
                                </a>

                                <form id="logout-form" action="{{ route('member.auth.post_logout') }}" method="POST" style="display: none;">
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
        @yield('content')
    </div>
@endsection
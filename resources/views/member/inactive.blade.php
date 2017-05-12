@extends('_layouts.main-template')

@section('title', 'Member Awaiting Approval')

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
                </ul>
            </div>
        </div>
    </nav>
    <div class="main-content" style="margin-top: 80px;">
        <div class="container">
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <div class="card">
                        <div class="card-header">
                            <h3><i class="fa fa-clock-o"></i> Account Inactive</h3>
                        </div>
                        <div class="card-block">
                            Hi {{ Auth::guard('member')->user()->first_name }}, Your account is awaiting approval.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
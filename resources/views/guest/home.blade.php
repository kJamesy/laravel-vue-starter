@extends('_layouts.main-template')

@section('title', 'Home')

@section('body')
    <nav class="navbar navbar-toggleable-md navbar-inverse bg-primary fixed-top">
        <div class="container">
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="{{ url('/') }}">Epsilon</a>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('member.home') }}">Member</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.home') }}">Admin</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
@endsection

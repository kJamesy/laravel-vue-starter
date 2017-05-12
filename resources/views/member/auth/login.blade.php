@extends('member._layouts.member-template')

@section('title', 'Member Login')

@section('active-login', 'active')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-10 offset-lg-1">
            <div class="card">
                <div class="card-header">Member Login</div>
                <div class="card-block">
                    <form method="POST" action="{{ route('member.auth.process_login') }}">
                        {{ csrf_field() }}

                        <div class="form-group row{{ $errors->has('username') ? ' has-danger' : '' }}">
                            <label for="username" class="col-md-4 form-control-label">Username or Email</label>

                            <div class="col-md-8">
                                <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' form-control-danger' : '' }}" name="username" value="{{ old('username') }}" autofocus>

                                @if ($errors->has('username'))
                                    <small class="form-control-feedback">
                                        {{ $errors->first('username') }}
                                    </small>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row{{ $errors->has('password') ? ' has-danger' : '' }}">
                            <label for="password" class="col-md-4 form-control-label">Password</label>

                            <div class="col-md-8">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' form-control-danger' : '' }}" name="password">

                                @if ($errors->has('password'))
                                    <small class="form-control-feedback">
                                        {{ $errors->first('password') }}
                                    </small>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-8 offset-md-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">Login</button>
                                <a class="btn btn-link" href="{{ route('member.auth.show_password_reset') }}">Forgot Your Password?</a> |
                                <a class="btn btn-link" href="{{ route('member.auth.show_registration') }}">Register</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

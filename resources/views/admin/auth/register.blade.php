@extends('admin._layouts.admin-template')

@section('title', 'Admin Registration')

@section('active-registration', 'active')

@section('content')
    <div class="container auth-container">
        <div class="row">
            <div class="col-lg-10 offset-lg-1">
                <div class="card">
                    <div class="card-header">Admin Registration</div>
                    <div class="card-block">
                        <form method="POST" action="{{ route('admin.auth.store_registration') }}">
                            {{ csrf_field() }}

                            <div class="form-group row{{ $errors->has('first_name') ? ' has-danger' : '' }}">
                                <label for="first_name" class="col-md-4 form-control-label">First Name</label>

                                <div class="col-md-8">
                                    <input id="first_name" type="text" class="form-control{{ $errors->has('first_name') ? ' form-control-danger' : '' }}" name="first_name" value="{{ old('first_name') }}" autofocus>

                                    @if ($errors->has('first_name'))
                                        <small class="form-control-feedback">
                                            {{ $errors->first('first_name') }}
                                        </small>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row{{ $errors->has('last_name') ? ' has-danger' : '' }}">
                                <label for="last_name" class="col-md-4 form-control-label">Last Name</label>

                                <div class="col-md-8">
                                    <input id="last_name" type="text" class="form-control{{ $errors->has('last_name') ? ' form-control-danger' : '' }}" name="last_name" value="{{ old('last_name') }}">

                                    @if ($errors->has('last_name'))
                                        <small class="form-control-feedback">
                                            {{ $errors->first('last_name') }}
                                        </small>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row{{ $errors->has('username') ? ' has-danger' : '' }}">
                                <label for="username" class="col-md-4 form-control-label">Username</label>

                                <div class="col-md-8">
                                    <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' form-control-danger' : '' }}" name="username" value="{{ old('username') }}">

                                    @if ($errors->has('username'))
                                        <small class="form-control-feedback">
                                            {{ $errors->first('username') }}
                                        </small>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row{{ $errors->has('email') ? ' has-danger' : '' }}">
                                <label for="email" class="col-md-4 form-control-label">Email</label>

                                <div class="col-md-8">
                                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' form-control-danger' : '' }}" name="email" value="{{ old('email') }}">

                                    @if ($errors->has('email'))
                                        <small class="form-control-feedback">
                                            {{ $errors->first('email') }}
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

                            <div class="form-group row{{ $errors->has('password') ? ' has-danger' : '' }}">
                                <label for="password-confirm" class="col-md-4 form-control-label">Confirm Password</label>

                                <div class="col-md-8">
                                    <input id="password-confirm" type="password" class="form-control{{ $errors->has('password') ? ' form-control-danger' : '' }}" name="password_confirmation">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">Register</button>
                                    <a class="btn btn-link" href="{{ route('admin.auth.show_login') }}">Login</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

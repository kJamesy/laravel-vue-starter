@extends('admin._layouts.admin-template')

@section('title', 'Admin Password Reset')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-10 offset-lg-1">
                <div class="card">
                    <div class="card-header">Reset Password</div>
                    <div class="card-block">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.auth.process_password_reset_form') }}">
                            {{ csrf_field() }}

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="form-group row{{ $errors->has('email') ? ' has-danger' : '' }}">
                                <label for="email" class="col-md-4 form-control-label">Email</label>

                                <div class="col-md-8">
                                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' form-control-danger' : '' }}" name="email" value="{{ $email or old('email') }}" autofocus>

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

                                    @if ($errors->has('password'))
                                        <small class="form-control-feedback">
                                            {{ $errors->first('password') }}
                                        </small>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Reset Password
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

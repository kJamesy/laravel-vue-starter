@extends('admin._layouts.admin-template')

@section('title', 'Admin Password Reset')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-10 offset-lg-1">
            <div class="card">
                <div class="card-header">Admin Password Reset</div>
                <div class="card-block">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.auth.send_password_reset_email') }}">
                        {{ csrf_field() }}

                        <div class="form-group row{{ $errors->has('email') ? ' has-danger' : '' }}">
                            <label for="email" class="col-md-4 form-control-label">Email</label>

                            <div class="col-md-8">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' form-control-danger' : '' }}" name="email" value="{{ old('email') }}" autofocus>

                                @if ($errors->has('email'))
                                    <small class="form-control-feedback">
                                        {{ $errors->first('email') }}
                                    </small>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">Send Link</button>
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

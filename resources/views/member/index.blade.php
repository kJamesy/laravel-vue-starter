@extends('member._layouts.member-template')

@section('title', $user->name)


@section('content')
    <div class="container">
        Welcome {{ $user->name }}
    </div>
@endsection
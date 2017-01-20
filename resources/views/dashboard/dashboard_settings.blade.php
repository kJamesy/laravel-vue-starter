@extends('layouts.app')

@section('view-title', 'Settings')

@section('view-header')
    <script>
        window.settingsLinks = {
            baseUrl: '{{ route('admin.settings') }}',
            baseUri: '{{ explode( $_SERVER['SERVER_NAME'], route('admin.settings'))[1] }}',
        }
    </script>
@endsection

@section('content')
<div class="container" id="settings-app">
    <settings>

    </settings>
</div>
@endsection

@extends('admin._layouts.admin-template')

@section('title', $user->name)

@section('view-header-styles-scripts')
    <script>
        window.links = {
            home: '{{ route('settings.index') }}',
            base: '{{ explode( $_SERVER['HTTP_HOST'], route('settings.index'))[1] }}',
        }
    </script>
@endsection

@section('dashboard_active', 'active')
@section('content')
    <div id="admin-app">
        <admin>

        </admin>
    </div>
@endsection
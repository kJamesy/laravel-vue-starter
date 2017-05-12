@extends('admin._layouts.admin-template')

@section('title', 'Users')

@section('view-header-styles-scripts')
    <script>
        window.permissionsKey = '{!! $permissionsKey !!}';
        window.settingsKey = '{!! $settingsKey !!}';
        window.links = {
            home: '{{ route('settings.index') }}',
            base: '{{ explode( $_SERVER['HTTP_HOST'], route('users.index'))[1] }}',
        }
    </script>
@endsection

@section('users_active', 'active')
@section('content')
    <div id="admin-users-app">
        <admin-users>

        </admin-users>
    </div>
@endsection
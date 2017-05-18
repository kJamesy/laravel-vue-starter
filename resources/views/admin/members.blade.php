@extends('admin._layouts.admin-template')

@section('title', 'Members')

@section('view-header-styles-scripts')
    <script>
        window.permissionsKey = '{!! $permissionsKey !!}';
        window.settingsKey = '{!! $settingsKey !!}';
        window.links = {
            home: '{{ route('settings.index') }}',
            base: '{{ explode( $_SERVER['SERVER_NAME'], route('members.index'))[1] }}',
        }
    </script>
@endsection

@section('members_active', 'active')
@section('content')
    <div id="admin-members-app">
        <admin-members>

        </admin-members>
    </div>
@endsection
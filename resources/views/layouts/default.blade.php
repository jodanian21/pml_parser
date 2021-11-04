<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @hasSection('title')
            @yield('title')
        @else
            {{ config('app.name') }}
        @endif
    </title>

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Default image paths --}}
    <meta name="thumbnail_path" content="{{ config('path.thumbnails') }}">
    <meta name="asset_path" content="{{ config('path.assets') }}">

    {{-- Styles --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet" />
</head>

<body>
    @include('component.header')
    
    <main role="main" class="container-fluid pt-3">
        @yield('content')
    </main>

    {{-- @include('admin.sections.footer') --}}

    {{-- @stack('modals') --}}

    {{-- Scripts --}}
    <script src="{{ mix('js/app.js') }}" defer></script>
    @stack('scripts')
</body>

</html>
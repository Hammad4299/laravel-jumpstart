<!DOCTYPE html>
<html class="no-js" lang="en">
    <head>
        <title>{{ config('app.name') }} - @yield('title')</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="description" content="@yield('description')" />
        <meta name="keyword" content="@yield('keywords')" />
        <meta name="charset" content="utf-8" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta http-equiv="x-ua-compatible" content="ie=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
        <link rel="stylesheet" href="{{ assetUrl('common.css') }}" />
        @yield('head')
        @yield('styles')
    </head>

    <body class="@yield('body-classes')">
        <div class="container body">
            <div class="main_container">
                @section('content-header')
                    @include('partials.public-header')
                @show
                @yield('content')
            </div>
        </div>
        @include('partials.templates')

        <script src="{{ assetUrl('runtime.js') }}"></script>
        <script src="{{ assetUrl('vendor.js') }}"></script>
        <script src="{{ assetUrl('common.js') }}"></script>
        @yield('scripts')
    </body>
</html>
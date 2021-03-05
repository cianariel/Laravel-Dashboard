{{-- Stored in resources/views/frontend/partials/head.blade.php --}}
<head>
    <meta charset="utf-8">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="@yield('meta-description', setting('site_meta_description', env('APP_DOMAIN'), true))">
    <meta name="keywords" content="@yield('meta-keywords', setting('site_meta_keywords', env('APP_DOMAIN'), true))">
    <meta name="robots" content="@yield('meta-robots', setting('site_meta_robots', env('APP_DOMAIN'), true))">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    @include('partials.twitter_cards')
    {{--[csrf-token]--}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{--[/csrf-token]--}}
    {{--[canonical]--}}
    <link rel="canonical" href="@yield('canonical-url', config('app.url'))" />
    {{--[/canonical]--}}
    {{--[title]--}}
    <title>{{ env('APP_NAME') }}@hasSection('html-title') - @endif @yield('html-title', setting('site_title', env('APP_DOMAIN'), true))</title>
    {{--[/title]--}}
    {{--[scripts]--}}
    @component('components.elements.script', ['src' => 'https://js.stripe.com/v3/'])@endcomponent
    @component('components.elements.script', ['src' => mix('js/manifest.js')])@endcomponent
    @component('components.elements.script', ['src' => mix('js/vendor.js')])@endcomponent
    @component('components.elements.script', ['src' => mix('js/app.js')])@endcomponent
    @component('components.elements.script', ['src' => mix('js/framework.js')])@endcomponent
    {{--[/scripts]--}}
    {{--[prefetch]--}}
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    {{--[/prefetch]--}}
    {{--[css]--}}
    @component('components.elements.style', ['type' => 'link', 'href' => mix('css/app.css')])@endcomponent
    {{--[/css]--}}
    @include('partials.nocaptcha')
    @yield('html_head')
    @if($css = setting('site_theme_css', env('APP_DOMAIN'), true))
    <style type="text/css">
        {!! $css !!}
    </style>
    @endif
</head>
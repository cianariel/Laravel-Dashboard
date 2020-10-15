{{-- Stored in resources/views/layouts/partials/head.blade.php --}}
<head>
    <base href="./">
    <meta charset="utf-8">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    @hasSection('meta-description')<meta name="description" content="@yield('meta-description')">@endif
    @hasSection('meta-keywords')<meta name="keywords" content="@yield('meta-keywords')">@endif
    @hasSection('meta-robots')<meta name="robots" content="@yield('meta-robots')">@endif
    {{--
    <meta name="author" content="GeekBidz, LLC">
    <link rel="apple-touch-icon" sizes="57x57" href="assets/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="assets/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="assets/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="assets/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="assets/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="assets/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="assets/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="assets/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="assets/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="assets/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="assets/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="assets/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    --}}
    @include('partials.twitter_cards')
    {{--[csrf-token]--}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{--[/csrf-token]--}}
    {{--[canonical]--}}
    <link rel="canonical" href="@yield('canonical-url', config('app.url'))" />
    {{--[/canonical]--}}
    {{--[title]--}}
    <title>@yield('html-title')@hasSection('html-title') - @endif{{ config('app.name', 'GeekBidz') }}</title>
    {{--[/title]--}}
    {{--[scripts]--}}
    {{--@component('components.elements.script', ['src' => 'https://js.stripe.com/v3/'])@endcomponent--}}
    @component('components.elements.script', ['src' => mix('js/manifest.js')])@endcomponent
    @component('components.elements.script', ['src' => mix('js/vendor.js')])@endcomponent
    @component('components.elements.script', ['src' => mix('js/app.js')])@endcomponent
    @component('components.elements.script', ['src' => mix('js/framework.js')])@endcomponent
    @component('components.elements.script', ['src' => mix('js/functions.js')])@endcomponent
    {{--[/scripts]--}}
    {{--[prefetch]--}}
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    {{--[/prefetch]--}}
    {{--[css]--}}
    @component('components.elements.style', ['type' => 'link', 'href' => mix('css/app.css')])@endcomponent
    {{--[/css]--}}
    @include('partials.nocaptcha')
    @yield('html_head')
</head>
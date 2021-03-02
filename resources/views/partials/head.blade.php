{{-- Stored in resources/views/layouts/partials/head.blade.php --}}
<head>
    <base href="./">
    <meta charset="utf-8">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    @hasSection('meta-description')<meta name="description" content="@yield('meta-description')">@endif
    @hasSection('meta-keywords')<meta name="keywords" content="@yield('meta-keywords')">@endif
    @hasSection('meta-robots')<meta name="robots" content="@yield('meta-robots')">@endif
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    @include('partials.twitter_cards')
    {{--[csrf-token]--}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{--[/csrf-token]--}}
    {{--[canonical]--}}
    <link rel="canonical" href="@yield('canonical-url', config('app.url'))" />
    {{--[/canonical]--}}
    {{--[title]--}}
    <title>@yield('html-title')@hasSection('html-title') - @endif{{ config('app.name', 'MDRepTime') }}</title>
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
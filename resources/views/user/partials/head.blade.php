{{-- Stored in resources/views/user/partials/head.blade.php --}}
<head>
    <meta charset="utf-8">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="@yield('meta-description', setting('site_meta_description', config('app.base_domain'), true))">
    <meta name="keywords" content="@yield('meta-keywords', setting('site_meta_keywords', config('app.base_domain'), true))">
    <meta name="robots" content="NOINDEX, NOFOLLOW">
    @include('partials.twitter_cards')
    {{--[csrf-token]--}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{--[/csrf-token]--}}
    {{--[canonical]--}}
    <link rel="canonical" href="@yield('canonical-url', config('app.url'))" />
    {{--[/canonical]--}}
    {{--[title]--}}
    <title>{{ env('APP_NAME') }}@hasSection('html-title') - @endif @yield('html-title', setting('site_title', config('app.base_domain'), true))</title>
    {{--[/title]--}}
    {{--[scripts]--}}
    @component('components.elements.script', ['src' => mix('js/manifest.js')])@endcomponent
    @component('components.elements.script', ['src' => mix('js/vendor.js')])@endcomponent
    @component('components.elements.script', ['src' => mix('js/app.js')])@endcomponent
    @component('components.elements.script', ['src' => mix('js/framework.js')])@endcomponent
    @component('components.elements.script', ['src' => 'https://cdn.jsdelivr.net/npm/fullcalendar@5.3.2/main.min.js'])@endcomponent
    @component('components.elements.script', ['src' => 'https://cdn.jsdelivr.net/npm/fullcalendar@5.3.2/locales-all.min.js'])@endcomponent
    {{--[/scripts]--}}
    {{--[prefetch]--}}
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    {{--[/prefetch]--}}
    {{--[css]--}}
    @component('components.elements.style', ['type' => 'link', 'href' => mix('css/app.css')])@endcomponent
    {{--[/css]--}}
    @include('partials.nocaptcha')
    @yield('html_head')
    @if($css = setting('site_theme_css', config('app.base_domain'), true))
    <style type="text/css">
        {!! $css !!}
    </style>
    @endif
</head>
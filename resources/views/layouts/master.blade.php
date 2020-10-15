{{-- Stored in resources/views/layouts/master.blade.php --}}
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="no-js">
    @yield('head')
    <body class="body page-@yield('page-class')"@hasSection('page-class') id="page-@yield('page-class')"@endif>
        @if($js = setting('site_theme_before_scripts', env('APP_DOMAIN'), true))
            {!! $js !!}
        @endif
        @yield('scripts_start')
        @component('components.bootstrap.card', ['id' => 'gb-app', 'classes' => ['border-0']])
            <div class="card-body border-0 p-0">
                 @yield('header')
                 @yield('content')
                 @yield('footer')
            </div>
        @endcomponent
        <script type="text/javascript">
        <!--
          let config = {
            locale: @json(config('app.locale')),
            domain : @json(config('app.domain')),
            site_url : @json(config('app.url')),
            csrf_token: @json(csrf_token()),
            current_url: @json(url()->current()),
            previous_url: @json(url()->previous()),
            stripe_pk: @json(env('STRIPE_API_KEY'))
          };

          const GB = GeekBidz.getInstance();
          GB.init(config);
        //-->
        </script>
        {{--[/scripts]--}}
        {{--[global-dialog]--}}@include('partials.dialog'){{--[/global-dialog]--}}
        @yield('scripts_end')
        @if($js = setting('site_theme_after_scripts', env('APP_DOMAIN'), true))
            @component('components.elements.script')
                {!! $js !!}
            @endcomponent
        @endif
    </body>
</html>
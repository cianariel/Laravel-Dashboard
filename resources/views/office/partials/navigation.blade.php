{{-- Stored in /resources/views/office/partials/navigation.blade.php --}}
{{--[navigation]--}}
<nav class="navbar navbar-expand-lg md-navbar">
    @component('components.elements.link', [
        'href'      => secure_url('office'),
        'classes'   => ['navbar-brand']
    ])
        <span class="site-name">
            @component('components.elements.image', [
                'src'       => asset('images/logo.png'),
                'attrs'     => [
                    'title' => config('app.name')
                ],
                'classes'   => ['logo']
            ])
            @endcomponent
        </span>
    @endcomponent
    {{--[toggler]--}}
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-frontend-collapse" aria-controls="navbar-frontend-collapse" aria-expanded="false" aria-label="Toggle navigation">
        <i class="icon fas fa-bars"></i>
    </button>
    {{--[/toggler]--}}
</nav>
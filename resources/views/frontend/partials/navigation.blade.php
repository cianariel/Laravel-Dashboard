{{-- Stored in /resources/views/frontend/layouts/partials/navigation.blade.php --}}
{{--[navigation]--}}
<nav class="navbar navbar-expand-lg md-navbar">
    @component('components.elements.link', [
        'href'      => secure_url('/'),
        'classes'   => ['navbar-brand']
    ])
        <span class="site-name">
            @component('components.elements.image', [
                'src'       => asset('images/logo.png'),
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
    {{--[navbar-collapse]--}}
    <div class="collapse navbar-collapse navbar-frontend-collapse" id="navbar-frontend-collapse">
        <div class="d-block w-100">
            <ul class="navbar-nav mr-auto justify-content-end">
                {{--[guest]--}}
                @guest
                    @if($menu = menu('primary-menu'))
                        @if($items = $menu->menuItems()->orderBy('position', 'asc')->cursor())
                            @if($items->count() !== 0)
                                @foreach($items as $item)
                                    <li class="nav-item">
                                    @component('components.elements.link', [
                                        'href'  =>  $item->url,
                                        'attrs' => [
                                            'title' => $item->title,
                                            'target' => $item->target,
                                        ],
                                        'classes'   => (filled($item->css_classes))? explode(',', $item->css_classes) : ['nav-link']
                                    ])
                                        {!! $item->label !!}
                                    @endcomponent
                                    </li>
                                @endforeach
                            @else
                                <li class="nav-item"><a class="nav-link" href="{{ secure_url('login') }}">{{ __('Login') }}</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ secure_url('register') }}">{{ __('Sign up') }}</a></li>
                            @endif
                        @endif
                    @endif
                @endguest
                {{--[/guest]--}}
                {{--[auth]--}}
                @auth
                    @if($role = Auth::user()->roles()->first()->name)
                        <li class="nav-item">
                            @if($role == App\Models\System\Role::SUPER_ADMIN || $role == App\Models\System\Role::ADMIN)
                                @component('components.elements.link', [
                                    'href'      => secure_url('admin'),
                                    'classes'   => ['nav-link']
                                ])
                                    {{ __('Dashboard') }}
                                @endcomponent
                            @endif
                            @if($role == App\Models\System\Role::OWNER || $role == App\Models\System\Role::GUEST)
                                @component('components.elements.link', [
                                    'href'      => route('office.dashboard'),
                                    'classes'   => ['nav-link']
                                ])
                                    {{ __('Dashboard') }}
                                @endcomponent
                            @endif
                            @if($role == App\Models\System\Role::USER)
                                @component('components.elements.link', [
                                    'href'      => secure_url('user'),
                                    'classes'   => ['nav-link']
                                ])
                                    {{ __('Dashboard') }}
                                @endcomponent
                            @endif
                        </li>
                    @endif
                    <li class="nav-item">
                        @component('components.forms.form', ['classes' => ['nav-link', 'fg-blue'], 'method' => 'POST', 'action' => route('logout') ])
                            <button type="submit" class="btn-unstyled fg-blue">{{ __('Logout') }} <i class="fas fa-sign-out-alt"></i></button>
                        @endcomponent
                    </li>
                @endauth
                {{--[/auth]--}}
            </ul>
        </div>
    </div>
    {{--[/navbar-collapse]--}}
</nav>
{{--[/navigation]--}}
{{-- Stored in /resources/views/user/partials/navigation.blade.php --}}
{{--[navigation]--}}
<nav class="navbar navbar-expand-lg md-navbar">
    @component('components.elements.link', [
        'href'      => secure_url('user'),
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
    <div class="collapse navbar-collapse navbar-frontend-collapse" id="navbar-frontend-collapse">
        <div class="d-block w-100">
            <ul class="navbar-nav mr-auto justify-content-end">
                <li class="nav-item @if(Route::getCurrentRoute()->getName() == 'user') active @endif">
                    @component('components.elements.link', [
                        'href'      => '#',
                        'classes'   => ['nav-link']
                    ])
                        {{ __('My Appointments') }}
                    @endcomponent
                </li>
                <li class="nav-item @if(Route::getCurrentRoute()->getName() == 'user.offices.index') active @endif">
                    @component('components.elements.link', [
                        'href'      => route('user.offices.index'),
                        'classes'   => ['nav-link']
                    ])
                        {{ __('Offices') }} 
                    @endcomponent
                </li>
                <li class="nav-item @if(Route::getCurrentRoute()->getName() == 'user.messages.index') active @endif">
                    @component('components.elements.link', [
                        'href'      => route('user.messages.index'),
                        'classes'   => ['nav-link']
                    ])
                        {{ __('Messages') }} 
                        <span class="badge badge-secondary">0</span>
                    @endcomponent
                    
                </li>
                <li class="nav-item @if(Route::getCurrentRoute()->getName() == 'user.setup.account') active @endif">
                    @component('components.elements.link', [
                        'href'      => route('user.setup.account'),
                        'classes'   => ['nav-link']
                    ])
                        {{ __('Account') }} 
                    @endcomponent
                </li>
                <li class="nav-item">
                    @component('components.elements.link', [
                        'href'      => route('user.profile.edit'),
                        'classes'   => [
                            'nav-link',
                            'nav-avator-link'
                        ]
                    ])
                        @component('components.elements.image',[
                            'src'   => avator(auth()->user(), 'thumb'),
                            'attrs' => [
                                'title' => 'Edit Profile'
                            ]
                        ])@endcomponent
                    @endcomponent
                </li>
                <li class="nav-item">
                    @component('components.forms.form', ['classes' => ['nav-link', 'fg-blue'], 'method' => 'POST', 'action' => route('logout') ])
                        <button type="submit" class="btn-unstyled fg-blue">{{ __('Logout') }} <i class="fas fa-sign-out-alt"></i></button>
                    @endcomponent
                </li>
            </ul>
        </div>
    </div>
</nav>

@component('components.elements.style', [])
    nav .nav-item{
        height: 41px;
    }
    @media(min-width: 1030px){
        nav .nav-item{
            margin-right: 10px;
        }
        nav .nav-item.active, nav .nav-item:hover{
            border-bottom: 2px solid #034ea2;
        }
    }        
@endcomponent
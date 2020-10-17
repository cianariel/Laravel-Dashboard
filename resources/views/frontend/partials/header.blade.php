{{-- Stored in resources/views/frontend/layouts/partials/header.blade.php --}}
{{--[header]--}}
<header id="md-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-block pt-3 pb-3 bg-blue mb-3">
                    <ul class="list-unstyled pull-right">
                        <li class="d-inline-block fg-white">
                            @component('components.elements.link', [
                                'href'      => route('register'),
                                'classes'   => ['fg-white']
                            ])
                                {{ __('Join') }}
                            @endcomponent
                        </li>
                        <li class="d-inline-block fg-white ml-2">
                            @component('components.elements.link', [
                                'href'      => route('login'),
                                'classes'   => ['fg-white']
                            ])
                                <i class="far fa-user"></i> {{ __('Login') }}
                            @endcomponent
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="md-navigation">
        <div class="container-fluid">
            @include('frontend.partials.navigation')
        </div>
    </div>
</header>
{{--[header]--}}
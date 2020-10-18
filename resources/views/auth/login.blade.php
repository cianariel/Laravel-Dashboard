{{-- Store in /resources/vies/auth/login.blade.php --}}
@extends('frontend.layouts.master')
@section('html-title', 'Login')
@section('content-body')
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-12 col-md-9">
                {{--[login]--}}
                @component('components.bootstrap.card', [
                    'id'        => 'register-card',
                    'classes'   => ['mt-3']
                ])
                    <div class="card-body bg-white">
                        <h3 class="mb-1">{{ __('Sign in') }}</h3>
                        <p>{{ __('to access MD Rep Time Account') }}</p>
                        <div class="d-block mb-4">
                            @component('components.elements.image',[
                                'src'       => asset('images/login_graphic.png'),
                                'classes'   => ['w-100']
                            ])@endcomponent
                        </div>
                        @component('components.forms.form', [
                            'id'        => 'register-form',
                            'method'    => 'POST',
                            'action'    => route('login')
                        ])
                        @component('components.forms.form', ['classes' => ['no-form-update-handler'], 'method' => 'POST', 'action' => route('login')])
                                @component('components.forms.input', [
                                    'type'          => 'email',
                                    'name'          => 'email',
                                    'label'         => 'Email',
                                    'value'         => old('email'),
                                    'placeholder'   => 'Enter your email address'
                                ])
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.input', [
                                    'type'          => 'password',
                                    'name'          => 'password',
                                    'label'         => 'Password',
                                    'value'         => '',
                                    'placeholder'   => 'Enter a password'
                                ])
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent

                                @component('components.forms.checkbox', [
                                    'name'      => 'remember',
                                    'label'     => 'Remember Me',
                                    'checked'   => old('remember')
                                ])
                                    @error('remember')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                <div class="form-group row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        @component('components.forms.button', [
                                            'name' => 'submit',
                                            'type' => 'submit',
                                            'classes' => ['btn', 'fg-white', 'bg-green'],
                                            'label' => __('Login')
                                        ])
                                        @endcomponent
                                        @if (Route::has('password.request'))
                                            @component('components.elements.link', [
                                                'href'      => route('password.request'),
                                                'classes'   => ['btn','btn-link','pl-0', 'font-sm-size']
                                            ])
                                                {{ __('Forgot Your Password?') }}
                                            @endcomponent
                                        @endif
                                    </div>
                                </div>
                            @endcomponent
                        @endcomponent
                    </div>
                @endcomponent
                {{--[/login]--}}
            </div>
        </div>
    </div>
@endsection
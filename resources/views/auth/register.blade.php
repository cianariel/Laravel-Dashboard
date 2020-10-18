{{-- Store in /resources/vies/auth/register.blade.php --}}
@extends('frontend.layouts.master')
@section('html-title', 'Register')
@section('content-body')
    <div class="container">
        <div class="col-12">
            @component('components.bootstrap.card', [
                'layout'  => 'card-deck'
            ])
                @component('components.bootstrap.card', [
                    'id'        => 'register-card',
                    'classes'   => ['mt-3']
                ])
                    <div class="card-body bg-white">
                        <h3 class="mb-3">{{ __('Register') }}</h3>
                        @component('components.forms.form', [
                            'id'        => 'register-form',
                            'method'    => 'POST',
                            'action'    => route('register')
                        ])
                            @component('components.forms.select', [
                                'id'        => 'account_type',
                                'name'      => 'account_type',
                                'label'     => 'Account Type',
                                'options'   => [
                                    __('Medical Office'),
                                    __('Industry representative')
                                ],
                                'value'     => old('account_type')
                            ])
                                @error('account_type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            @endcomponent
                            @component('components.forms.input', [
                                'type'          => 'text',
                                'id'            => 'company',
                                'name'          => 'company',
                                'label'         => __('Company'),
                                'value'         => old('company'),
                                'placeholder'   => __('MD Rep Time, LLC')
                            ])
                            @endcomponent
                            @component('components.forms.input', [
                                'type'          => 'text',
                                'id'            => 'first_name',
                                'name'          => 'first_name',
                                'label'         => __('First Name'),
                                'value'         => old('first_name'),
                                'placeholder'   =>__('Enter your first name')
                            ])
                            @endcomponent
                            @component('components.forms.input', [
                                'type'          => 'text',
                                'id'            => 'last_name',
                                'name'          => 'last_name',
                                'label'         => __('Last Name'),
                                'value'         => old('last_name'),
                                'placeholder'   => __('Enter your last name')
                            ])
                            @endcomponent
                            @component('components.forms.input', [
                                'type'          => 'email',
                                'id'            => 'email',
                                'name'          => 'email',
                                'label'         => __('Email Address'),
                                'value'         => old('email'),
                                'placeholder'   => __('Enter your email address')
                            ])
                            @endcomponent
                            @component('components.forms.password', [
                                'type'          => 'password',
                                'name'          => 'password',
                                'label'         => __('Password'),
                                'value'         => '',
                                'placeholder'   => __('Enter a password')
                            ])
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            @endcomponent
                            @component('components.forms.recaptcha', [
                                'label'         => 'Human Verfication',
                                'name'          => 'g-recaptcha'
                            ])
                                @error('g-recaptcha')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            @endcomponent
                            <div class="row">
                                <div class="col-12 offset-md-4">
                                    @component('components.forms.button', [
                                        'id'        => 'signup-btn',
                                        'type'      => 'submit',
                                        'name'      => 'signup-btn',
                                        'label'     => __('Sign up'),
                                        'classes'   => ['btn', 'fg-white', 'bg-green'],
                                    ])
                                    @endcomponent
                                </div>
                            </div>
                        @endcomponent
                    </div>
                @endcomponent
                {{--[/register]--}}
                {{--[login]--}}
                    @component('components.bootstrap.card', [
                        'id'        => 'register-card',
                        'classes'   => ['mt-3']
                    ])
                        <div class="card-body bg-white">
                            <h3 class="mb-1">{{ __('Sign in') }}</h3>
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
                                                <a class="btn btn-link pl-0" href="{{ route('password.request') }}">
                                                    {{ __('Forgot Your Password?') }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endcomponent
                            @endcomponent
                        </div>
                    @endcomponent
                {{--[/login]--}}
            @endcomponent
        </div>
    </div>
@endsection
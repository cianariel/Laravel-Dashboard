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
                                        'classes'   => ['btn', 'btn-primary', 'bg-green']
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
                            <p>{{ __('to access MD Rep Time Account') }}</p>
                            @component('components.forms.form', [
                                'id'        => 'register-form',
                                'method'    => 'POST',
                                'action'    => route('login')
                            ])
                            @endcomponent
                        </div>
                    @endcomponent
                {{--[/login]--}}
            @endcomponent
        </div>
    </div>
@endsection
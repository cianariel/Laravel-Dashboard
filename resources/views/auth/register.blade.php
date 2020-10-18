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
                    <div class="card-header">
                        {{ __('Register') }}
                    </div>
                    <div class="card-body bg-white">
                        @component('components.forms.form', [
                            'id'        => 'register-form',
                            'method'    => 'POST',
                            'action'    => route('register')
                        ])
                            @component('components.forms.select', [
                                'id'        => 'account_type',
                                'name'      => 'account_type',
                                'label'     => 'Account Type',
                                'options'   => ['Office', 'Rep'],
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
                                'placeholder'   => __('John / Jane')
                            ])
                            @endcomponent
                            @component('components.forms.input', [
                                'type'          => 'text',
                                'id'            => 'last_name',
                                'name'          => 'last_name',
                                'label'         => __('Last Name'),
                                'value'         => old('last_name'),
                                'placeholder'   => __('Doe')
                            ])
                            @endcomponent
                            @component('components.forms.input', [
                                'type'          => 'email',
                                'id'            => 'email',
                                'name'          => 'email',
                                'label'         => __('Email Address'),
                                'value'         => old('email'),
                                'placeholder'   => __('me@mdreptime.com')
                            ])
                            @endcomponent
                        @endcomponent
                    </div>
                @endcomponent
                {{--[/register]--}}
                {{--[login]--}}
                    @component('components.bootstrap.card', [
                        'id'        => 'register-card',
                        'classes'   => ['mt-3']
                    ])
                        <div class="card-header">
                            {{ __('Login') }}
                        </div>
                        <div class="card-body bg-white">
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
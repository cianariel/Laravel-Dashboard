{{-- Stored in /resources/views/admin/users/show.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'User Details')
@section('page-class', 'admin-users-show')
@if(isset($breadcrumbs))
    @section('breadcrumbs')
        @component('components.elements.breadcrumbs', ['list' => $breadcrumbs])
        @endcomponent
    @endsection
@endif
@section('content-body')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="header">
                <span class="font-lg-size font-weight-bold">{{ __('User Details') }}</span>
            </div>
            <div class="card-deck">
                <div class="card">
                    <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('General Information') }}</span></div>
                    <div class="card-body p-0 pt-3 pb-5">
                        <div class="row justify-content-center">
                            <div class="col-10">
                                <div class="row mb-2">
                                    <div class="col-4">
                                        {{ __('Email') }}
                                    </div>
                                    <div class="col-8">
                                        {{ $user->email }}
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4">
                                        {{ __('Username') }}
                                    </div>
                                    <div class="col-8">
                                        {{ $user->username }}
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4">
                                        {{ __('Company') }}
                                    </div>
                                    <div class="col-8">
                                        {{ $user->company }}
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4">
                                        {{ __('First Name') }}
                                    </div>
                                    <div class="col-8">
                                        {{ $user->first_name }}
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4">
                                        {{ __('Last Name') }}
                                    </div>
                                    <div class="col-8">
                                        {{ $user->last_name }}
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4">
                                        {{ __('Address') }}
                                    </div>
                                    <div class="col-8">
                                        <address>
                                            @if(filled($user->address))
                                                 {{ $user->address }}<br>
                                            @endif
                                            @if(filled($user->address_2))
                                                {{ $user->address_2 }}<br>
                                            @endif
                                            @if(filled($user->city) && filled($user->state) && filled($user->zipcode))
                                                {{ $user->city }}, {{ $user->state }} {{ $user->zipcode }}<br>
                                            @endif
                                            @if(filled($user->country))
                                                {{ country($user->country) }}
                                            @endif
                                        </address>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4">
                                        {{ __('Phone') }}
                                    </div>
                                    <div class="col-8">
                                        {{ $user->phone }}
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4">
                                        {{ __('Mobile Phone:') }}
                                    </div>
                                    <div class="col-8">
                                        {{ $user->mobile_phone }}
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4">
                                        {{ __('Role') }}
                                    </div>
                                    <div class="col-8">
                                        {{ ucwords($user->roles()->first()->label) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-10 text-right">
                                <a class="btn btn-secondary" href="{{ route('admin.users.index') }}">{{ __('Back') }}</a>
                                <a class="btn btn-primary" href="{{ route('admin.users.edit', $user) }}">{{ __('Edit') }} <i class="fas fa-edit"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('Other Information') }}</span></div>
                    <div class="card-body p-0 pt-3 pb-3">
                        <div class="row justify-content-center">
                            <div class="col-md-10">
                                <div class="row mb-2">
                                    <div class="col-4">
                                        {{ __('Memeber Since') }}
                                    </div>
                                    <div class="col-8">
                                        {{ Carbon\Carbon::parse($user->created_at)->diffForHumans() }}
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4">
                                        {{ __('Last Login') }}
                                    </div>
                                    <div class="col-8">
                                        {{ Carbon\Carbon::parse($user->last_login_at)->diffForHumans()}}
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4">
                                        {{ __('IP Address') }}
                                    </div>
                                    <div class="col-8">
                                        {{ $user->ip_address }}
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4">
                                        {{ __('User-Agent') }}
                                    </div>
                                    <div class="col-8">
                                        {{ $user->user_agent }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
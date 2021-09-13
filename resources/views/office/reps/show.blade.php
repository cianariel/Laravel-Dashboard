{{-- Stored in /resources/views/office/reps/show.blade.php --}}
@extends('office.layouts.master')
@section('html-title', 'Rep Profile')
@section('page-class', 'office-reps-user-show')
@section('content-body')
    @component('components.bootstrap.container', [
        'fluid'  => false
    ])
        <div class="row">
            <div class="col-12">
                @component('components.bootstrap.card', [
                    'id'        => 'office-reps-user-show-card',
                    'classes'   => [
                        'border-0',
                        'p-0'
                    ]
                ])
                    <div class="card-body p-0">
                        {{--[user-profile-header]--}}
                        <div id="office-rep-user-profile-header" class="office-rep-user-profile-header d-block p-5">
                            <div class="row">
                                <div class="col-4 col-md-2">
                                    <div class="user-avator image">
                                        <img src="{{ avator($repUser) }}">
                                    </div>
                                </div>
                                <div class="col-8 col-md-10">
                                    @component('components.elements.link', [
                                            'href'      => route('office.reps.show', $repUser->username),
                                            'classes'   => [
                                                'border-0',
                                                'text-decoration-none',
                                                'fg-white'
                                            ]
                                        ])
                                        <h5>{{ $repUser->first_name }} {{ $repUser->last_name }}</h5>
                                    @endcomponent
                                    @if($drugs = $repUser->getMetaField('drugs', null))
                                        <h6 class="font-weight-normal fg-white">{{ $repUser->company }} {{ __('for') }} {{ implode(', ', $drugs) }}</h6>
                                    @endif
                                </div>
                            </div>
                        </div>
                        {{--[/user-profile-header]--}}
                        @include('office.reps.partials.profile_toolbar')
                        {{--[user-profile-body]--}}
                        <div id="office-rep-user-profile-body" class="office-rep-user-profile-body">
                            @component('components.bootstrap.card', [
                                'layout'    => 'card-deck'
                            ])
                                @include('office.reps.partials.user_sidebar')
                            @endcomponent
                        </div>
                        {{--[/user-profile-body]--}}
                    </div>
                @endcomponent
            </div>
        </div>
    @endcomponent
@endsection
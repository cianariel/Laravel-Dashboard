{{-- Stored in /resources/views/office/reps/index.blade.php --}}
@extends('office.layouts.master')
@section('html-title', 'Reps Database')
@section('page-class', 'office-reps-database-index')
@section('content-body')
    @component('components.bootstrap.container', [
        'fluid'  => true
    ])
        @component('components.bootstrap.card', [
            'id'        => 'office-reps-database-card-deck',
            'layout'    => 'card-group'
        ])
            @include('office.reps.partials.sidebar')
            @component('components.bootstrap.card', [
                'id'    => 'office-reps-database-listing-card'
            ])
                <div class="card-body">
                    @if($reps->count() !== 0)
                        <ul class="list-group">
                            @foreach($reps as $rep)
                                <li class="list-group-item">
                                    @component('components.elements.link', [
                                        'href'  => '#'
                                    ])
                                        <div class="row">
                                            <div class="col-12 col-md-2">
                                                <div class="user-avator image">
                                                    <img src="{{ avator($rep) }}">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-10">
                                                <h5>{{ $rep->first_name }} {{ $rep->last_name }}</h5>
                                                <h6 class="font-weight-normal">{{ __('Place holder for RX Data') }}</h6>
                                            </div>
                                        </div>
                                        <div class="row mt-1">
                                            <div class="col-12">
                                                <ul class="list-unstyled">
                                                    <li class="d-inline-block mr-3">
                                                        @component('components.elements.link', [
                                                            'href'  => '#',
                                                        ])
                                                            <i class="far fa-check-square"></i> {{ __('Approve') }}
                                                        @endcomponent
                                                    </li>
                                                    <li class="d-inline-block mr-3">
                                                        @component('components.elements.link', [
                                                            'href'  => '#',
                                                        ])
                                                            <i class="far fa-heart"></i> {{ __('Favorite') }}
                                                        @endcomponent
                                                    </li>
                                                    <li class="d-inline-block mr-3">
                                                        @component('components.elements.link', [
                                                            'href'  => '#',
                                                        ])
                                                            <i class="fas fa-ban"></i> {{ __('Block') }}
                                                        @endcomponent
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    @endcomponent
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="card-text text-center">{{ __('No reps available.') }}</p>
                    @endif
                </div>
            @endcomponent
        @endcomponent
    @endcomponent
@endsection
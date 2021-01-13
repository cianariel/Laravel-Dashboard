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
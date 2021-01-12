{{-- Stored in /resources/views/office/reps/index.blade.php --}}
@extends('office.layouts.master')
@section('html-title', 'Reps Database')
@section('page-class', 'office-reps-database-index')
@section('content-body')
    @component('components.bootstrap.container', [
        'id'        => 'office-reps-database-card-deck',
        'layout'    => 'card-group'
    ])
        @include('office.reps.partials.sidebar')
        @component('components.bootstrap.card', [
            'id'    => 'office-reps-database-listing-card'
        ])
            <div class="card-body">
            </div>
        @endcomponent
    @endcomponent
@endsection
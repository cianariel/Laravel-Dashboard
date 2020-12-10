{{-- Stored in /resources/views/office/calendar/index.blade.php --}}
@extends('office.layouts.master')
@section('html-title', 'Messages')
@section('page-class', 'office-messages-index')
{{--[content]--}}
@section('content-body')
    @component('components.bootstrap.container', [
        'fluid'     => true,
        'classes'   => [
            'mt-3'
        ]
    ])
        <div class="row">
            <div class="col-12">
                @component('components.bootstrap.card', [
                    'layout'    => 'card-group'
                ])
                    @include('office.messages.partials.sidebar')
                    @component('components.bootstrap.card', [
                        'id'    => 'office-messges-card'
                    ])
                    @endcomponent
                @endcomponent
            </div>
        </div>
    @endcomponent
@endsection
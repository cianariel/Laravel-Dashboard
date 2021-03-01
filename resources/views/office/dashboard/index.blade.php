{{-- Stored in /resources/views/office/dashboard/index.blade.php --}}
@extends('office.layouts.master')
@section('html-title', 'Office Dashboard')
@section('page-class', 'office-dashboard')
{{--[content]--}}
@section('content-body')
    @component('components.bootstrap.card', [
        'layout'    => 'card-deck'
    ])
        @component('components.bootstrap.card', [
            'id'    => 'office-calendar-card'
        ])
            <div class="card-body">
                @component('components.elements.fullcalendar', [
                    'id'        => 'office-dashboard-calendar',
                    'options'   => $calendarOptions??  [
                        'themeSystem'   => 'bootstrap',
                        'initialView'   =>'dayGridMonth'
                    ]
                ])@endcomponent
            </div>
        @endcomponent
    @endcomponent
    @include('office.calendar.partials.add_event')
@endsection
{{--[/content]--}}
{{-- Stored in /resources/views/office/dashboard/index.blade.php --}}
@extends('office.layouts.master')
@section('html-title', 'Office Dashboard')
@section('page-class', 'office-dashboard')
{{--[content]--}}
@section('content-body')
    @component('components.bootstrap.card', [
        'id'    => 'office-calendar-card'
    ])
        <div class="card-body">
            <dov class="row">
                <div class="col-12">
                    @include('office.calendar.partials.add_calendar_event_link')
                    @component('components.elements.link', [
                        'id'        => 'refresh-page-btn',
                        'href'      => url()->full(),
                        'classes'   =>  [
                            'btn',
                            'btn-dark'
                        ]
                    ])
                        <i class="fas fa-redo"></i> {{ __('Refresh') }}
                    @endcomponent
                </div>
            </dov>
            @component('components.elements.fullcalendar', [
                'id'        => 'office-dashboard-calendar',
                'options'   => $calendarOptions??  [
                    'themeSystem'   => 'bootstrap',
                    'initialView'   =>'dayGridMonth'
                ]
            ])@endcomponent
        </div>
    @endcomponent
    @include('office.calendar.partials.add_event')
@endsection
{{--[/content]--}}
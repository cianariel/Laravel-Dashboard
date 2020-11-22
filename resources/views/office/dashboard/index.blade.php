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
                    'id'    => 'office-dashboard-calendar'
                ])@endcomponent
            </div>
        @endcomponent
        @component('components.bootstrap.card', [
            'id'    => 'office-calendar-sidebar-card'
        ])
            <div class="card-body">

            </div>
        @endcomponent
    @endcomponent
@endsection
{{--[/content]--}}
{{--[scripts]--}}
@section('scripts_end')
<script>
<!--
    jQuery(document).ready(function($){
        let mdOfficeFullCalendarBlock = $('#office-dashboard-calendar');
        let mdFullCalendar = new FullCalendar.Calendar(mdOfficeFullCalendarBlock[0], {
            themeSystem: 'bootstrap',
            initialView: 'dayGridMonth'
        });

        mdFullCalendar.render();
    });
//-->
</script>
@endsection
{{--[/scripts]--}}
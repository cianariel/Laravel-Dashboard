{{-- Stored in /resources/views/office/calendar/index.blade.php --}}
@extends('office.layouts.master')
@section('html-title', 'Calendar')
@section('page-class', 'office-calendar')
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
                    'layout' => 'card-group'
                ])
                    @include('office.calendar.partials.sidebar')
                    @component('components.bootstrap.card', [
                        'id'        => 'office-calendar-index-card',
                        'classes'   => [
                        ]
                    ])
                        <div class="card-header border-0">
                            @include('office.calendar.partials.toolbar')
                        </div>
                        <div class="card-body">
                            @if($calendarEvents->count())
                            @component('components.elements.table_data',[
                                'headers' => [
                                    '',
                                    '',
                                    ''
                                ],
                                'classes' => [
                                    'table-hover'
                                ]
                            ])
                                @foreach($calendarEvents as $calendarEvent)
                                    <tr>
                                        <td width="15%">
                                            <strong>{{ Carbon\Carbon::parse($calendarEvent->start_at)->format('j') }}</strong>
                                            <span>{{ Carbon\Carbon::parse($calendarEvent->start_at)->format('D') }}</span>
                                        </td>
                                        <td>

                                        </td>
                                        <td class="text-right">

                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right">

                                        </td>
                                    </tr>
                                @endforeach
                            @endcomponent
                            @else
                                <p class="card-text text-center">{{ __('No calendar events found.') }}</p>
                            @endif
                        </div>
                    @endcomponent
                @endcomponent
            </div>
        </div>
    @endcomponent
@endsection
{{--[content]--}}
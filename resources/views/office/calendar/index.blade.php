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
                                        <td width="15%" class="text-center">
                                            @if(filled($calendarEvent->getMetaField('repeat_type')))
                                                @if($calendarEvent->getMetaField('repeat_type') == App\Models\System\CalendarEvent::REPEAT_WEEKLY)
                                                    {{ __($calendarEvent->getMetaField('repeat_day')) }}
                                                @endif
                                            @else
                                                <h4>{{ Carbon\Carbon::parse($calendarEvent->start_at)->format('j') }}</h4>
                                                <span>{{ Carbon\Carbon::parse($calendarEvent->start_at)->format('D') }}</span>
                                            @endif
                                            <div class="d-block mt-2">
                                                <i class="fas fa-ellipsis-h"></i>
                                            </div>
                                        </td>
                                        <td>
                                            @if(filled($calendarEvent->getMetaField('repeat_type')))
                                                <h4>{{ $calendarEvent->title }}</h4>
                                                <span>
                                                    @if($calendarEvent->recurring == App\Models\System\CalendarEvent::RECURRING)
                                                        {{ __('Recurring') }}
                                                    @endif
                                                </span>
                                            @else

                                            @endif
                                        </td>
                                        <td class="text-right">

                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right">
                                            @if(filled($calendarEvent->start_at))
                                                {{ Carbon\Carbon::parse($calendarEvent->start_at)->format('h:i A') }}
                                            @endif
                                            @if(filled($calendarEvent->ends_at))
                                                - {{ Carbon\Carbon::parse($calendarEvent->ends_at)->format('h:i A') }}
                                            @endif
                                            <div class="d-block mt-3">
                                                @component('components.elements.link', [
                                                    'href'      => '#',
                                                    'classes'   => [
                                                        'fg-link'
                                                    ]
                                                ])
                                                    <i class="far fa-edit"></i> <span class="text-uppercase">{{ __('Edit') }}</span>
                                                @endcomponent
                                            </div>
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
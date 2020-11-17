@extends('office.layouts.master')
@section('html-title', 'Edit Appointments Settings')
@section('page-class', 'office-settings-appointments-edit')
{{--[content]--}}
@section('content-body')
    @component('components.bootstrap.container', [
        'fluid'     => true,
        'classes'   => [
            'mt-3'
        ]
    ])
        <div class="row justify-content-center">
            <div class="col-12 col-md-12">
                @component('components.bootstrap.card', [
                    'layout'    => 'card-group'
                ])
                    @include('office.settings.partials.sidebar')
                    @component('components.bootstrap.card', [
                        'id' => 'office-settings-appointments-edit-card'
                    ])
                        <div class="card-header bg-white border-0">
                            <ul class="nav">
                                @component('components.elements.link', [
                                    'href'      => route('office.settings.edit.general.section', ['section' => 'office_info']),
                                    'classes'   => [
                                        'nav-link'
                                    ]
                                ])
                                    {{ __('Office Info') }}
                                @endcomponent
                                @component('components.elements.link', [
                                    'href'      =>  route('office.settings.edit.general.section', ['section' => 'holidays']),
                                    'classes'   => [
                                        'nav-link'
                                    ]
                                ])
                                    {{ __('Holidays') }}
                                @endcomponent
                                @component('components.elements.link', [
                                    'href'      =>  route('office.settings.edit.general.section', ['section' => 'office_hours']),
                                    'classes'   => [
                                        'nav-link'
                                    ]
                                ])
                                    {{ __('Office Hours') }}
                                @endcomponent
                                @component('components.elements.link', [
                                    'href'      =>  route('office.settings.edit.general.section', ['section' => 'visitation_rules']),
                                    'classes'   => [
                                        'nav-link'
                                    ]
                                ])
                                    {{ __('Vistation Rules') }}
                                @endcomponent
                                @component('components.elements.link', [
                                    'href'      =>  route('office.settings.edit.general.section', ['section' => 'rep_assignment']),
                                    'classes'   => [
                                        'nav-link'
                                    ]
                                ])
                                    {{ __('Rep Assignment Tool') }}
                                @endcomponent
                                @component('components.elements.link', [
                                    'href'      =>  route('office.settings.edit.general.section', ['section' => 'recurring_appointments']),
                                    'classes'   => [
                                        'nav-link'
                                    ]
                                ])
                                    {{ __('Recurring Appointments') }}
                                @endcomponent
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-12 col-md-12">
                                    @switch($section)
                                        @case('office_info')
                                            @include('office.settings.partials.office_info')
                                            @break;
                                        @case('holidays')
                                            @include('office.settings.partials.holidays')
                                            @break
                                        @case('office_hours')
                                            @include('office.settings.partials.office_hours')
                                            @break
                                        @case('visitation_rules')
                                            @include('office.settings.partials.visitation_rules')
                                            @break
                                        @case('rep_assignment')
                                            @include('office.settings.partials.rep_assignment')
                                            @break
                                        @case('recurring_appointments')
                                            @include('office.settings.partials.office_hours')
                                            @break
                                    @endswitch
                                </div>
                            </div>
                        </div>
                    @endcomponent
                @endcomponent
            </div>
        </div>
    @endcomponent
@endsection
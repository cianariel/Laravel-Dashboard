{{-- Stored in resources/views/office/calendar/partials/sidebar.blade.php }} --}}
@component('components.bootstrap.card', [
    'id'    => 'office-calendar-sidebar-card'
])
    <div class="card-body border-0">
        @component('components.forms.form',[
            'id'        => 'office-calendar-sidebar-form',
            'method'    => 'GET',
            'action'    => '#'
        ])
            <h6 class="card-text mb-3 text-uppercase font-weight-normal">{{ __('Appointment Status') }}</h6>
            {{--[open]--}}
            <div class="row">
                <div class="col-2">
                    @component('components.forms.checkbox', [
                        'id'        => 'appointment-status-open',
                        'name'      => 'appointment[status][open]',
                        'value'     => old('appointment.status.open')?? 'on',
                        'checked'   => (old('appointment.status.open') == 'on')? true : false,
                        'classes'   => [
                            'text-right',
                            'mb-0'
                        ]
                    ])
                        @error('appointment.status.open')
                            <span class="invalid-feedback" role="alert">
                                <span>{{ $message }}</span>
                            </span>
                        @enderror
                    @endcomponent
                </div>
                <div class="col-9"><label for="appointment-status-open">{{ __('Open') }}</label></div>
            </div>
            {{--[/open]--}}
            {{--[booked]--}}
            <div class="row">
                <div class="col-2">
                    @component('components.forms.checkbox', [
                        'id'        => 'appointment-status-booked',
                        'name'      => 'appointment[status][booked]',
                        'value'     => old('appointment.status.booked')?? 'on',
                        'checked'   => (old('appointment.status.booked') == 'on')? true : false,
                        'classes'   => [
                            'text-right',
                            'mb-0'
                        ]
                    ])
                        @error('appointment.status.booked')
                            <span class="invalid-feedback" role="alert">
                                <span>{{ $message }}</span>
                            </span>
                        @enderror
                    @endcomponent
                </div>
                <div class="col-9"><label for="appointment-status-booked">{{ __('Booked') }}</label></div>
            </div>
            {{--[/booked]--}}
            {{--[confirmed]--}}
            <div class="row">
                <div class="col-2">
                    @component('components.forms.checkbox', [
                        'id'        => 'appointment-status-confirmed',
                        'name'      => 'appointment[status][confirmed]',
                        'value'     => old('appointment.status.confirmed')?? 'on',
                        'checked'   => (old('appointment.status.confirmed') == 'on')? true : false,
                        'classes'   => [
                            'text-right',
                            'mb-0'
                        ]
                    ])
                        @error('appointment.status.confirmed')
                            <span class="invalid-feedback" role="alert">
                                <span>{{ $message }}</span>
                            </span>
                        @enderror
                    @endcomponent
                </div>
                <div class="col-9"><label for="appointment-status-confirmed">{{ __('Confirmed') }}</label></div>
            </div>
            {{--[/confirmed]--}}
            @if($office->getMetaField('recurring_appointments', false))
                <h6 class="card-text mb-3 text-uppercase font-weight-normal">{{ __('Appointment Type') }}</h6>
                {{--[breakfast]--}}
                <div class="row">
                    <div class="col-2">
                        @component('components.forms.checkbox', [
                            'id'        => 'appointment-type-breakfast',
                            'name'      => 'appointment[type][breakfast]',
                            'value'     => old('appointment.type.breakfast')?? 'on',
                            'checked'   => (old('appointment.type.breakfast') == 'on')? true : false,
                            'classes'   => [
                                'text-right',
                                'mb-0'
                            ]
                        ])
                            @error('appointment.type.breakfast')
                                <span class="invalid-feedback" role="alert">
                                    <span>{{ $message }}</span>
                                </span>
                            @enderror
                        @endcomponent
                    </div>
                    <div class="col-9"><label for="appointment-type-breakfast">{{ __('Breakfast') }}</label></div>
                </div>
                {{--[/breakfast]--}}
            @endif
        @endcomponent
    </div>
@endcomponent
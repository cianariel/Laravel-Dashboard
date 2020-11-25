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
                <div class="col-9"><label for="appointment-status-confirmed">{{ __('Open') }}</label></div>
            </div>
            {{--[/confirmed]--}}
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
            {{--[lunch]--}}
            <div class="row">
                <div class="col-2">
                    @component('components.forms.checkbox', [
                        'id'        => 'appointment-type-lunch',
                        'name'      => 'appointment[type][lunch]',
                        'value'     => old('appointment.type.lunch')?? 'on',
                        'checked'   => (old('appointment.type.lunch') == 'on')? true : false,
                        'classes'   => [
                            'text-right',
                            'mb-0'
                        ]
                    ])
                        @error('appointment.type.lunch')
                            <span class="invalid-feedback" role="alert">
                                <span>{{ $message }}</span>
                            </span>
                        @enderror
                    @endcomponent
                </div>
                <div class="col-9"><label for="appointment-type-lunch">{{ __('Lunch') }}</label></div>
            </div>
            {{--[/lunch]--}}
            {{--[dinner]--}}
            <div class="row">
                <div class="col-2">
                    @component('components.forms.checkbox', [
                        'id'        => 'appointment-type-dinner',
                        'name'      => 'appointment[type][dinner]',
                        'value'     => old('appointment.type.dinner')?? 'on',
                        'checked'   => (old('appointment.type.dinner') == 'on')? true : false,
                        'classes'   => [
                            'text-right',
                            'mb-0'
                        ]
                    ])
                        @error('appointment.type.dinner')
                            <span class="invalid-feedback" role="alert">
                                <span>{{ $message }}</span>
                            </span>
                        @enderror
                    @endcomponent
                </div>
                <div class="col-9"><label for="appointment-type-dinner">{{ __('Dinner') }}</label></div>
            </div>
            {{--[/dinner]--}}
            {{--[coffee]--}}
            <div class="row">
                <div class="col-2">
                    @component('components.forms.checkbox', [
                        'id'        => 'appointment-type-coffee',
                        'name'      => 'appointment[type][coffee]',
                        'value'     => old('appointment.type.coffee')?? 'on',
                        'checked'   => (old('appointment.type.coffee') == 'on')? true : false,
                        'classes'   => [
                            'text-right',
                            'mb-0'
                        ]
                    ])
                        @error('appointment.type.coffee')
                            <span class="invalid-feedback" role="alert">
                                <span>{{ $message }}</span>
                            </span>
                        @enderror
                    @endcomponent
                </div>
                <div class="col-9"><label for="appointment-type-coffee">{{ __('Coffee') }}</label></div>
            </div>
            {{--[/coffee]--}}

        @endcomponent
    </div>
@endcomponent
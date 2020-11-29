{{-- Stored in /resources/views/office/settings/recurring_appointments.blade.php --}}
@component('components.bootstrap.card', [
    'id'        => 'office-settings-recurring-appointments-card',
    'classes'   => [
        'border-0'
    ]
])
    <div class="card-header border-0 bg-white text-right">
        @component('components.elements.link', [
            'id'        => 'add-recurring-appointment-btn',
            'href'      => '#',
            'classes'   => [
                'btn',
                'btn-primary'
            ]
        ])
            {{ __('Add Recurring Appointment') }}
        @endcomponent
    </div>
    <div class="card-body">
        @if($office->getMetaField('recurring_appointments', false))
        @else
            <p class="card-text text-center">{{ __('No recuring appointments found.') }}</p>
        @endif
    </div>
@endcomponent
{{--[modal]--}}
@component('components.bootstrap.modal', [
    'id'        => 'office-settings-recurring-appointments-modal',
    'title'     => 'Add Recurring Appointment',
    'size'      => 'modal-lg'
])
    <div class="modal-body">
        <div class="row">
            <div class="col-md-10">
                @component('components.forms.form', [
                    'id'        => 'office-settings-recurring-appointments-modal-form',
                    'action'    => '#',
                    'method'    => 'POST',
                ])
                    {{--[recurring-appointment-type]--}}
                    @component('components.forms.input', [
                        'type'  => 'text',
                        'id'    => 'recurring-appointments-type',
                        'name'  => 'recurring_appointments_type',
                        'value' => old('recurring_appointments_type'),
                        'label' => __('Type'),
                        'attrs' => [
                            'required'  => 'required'
                        ],
                        'placeholder'   => __('Lunch')
                    ])
                        @error('recurring_appointments_type')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    @endcomponent
                    {{--[/recurring-appointment-type]--}}
                    {{--[section-type]--}}
                    @component('components.forms.select',[
                        'id'            => 'section-type',
                        'name'          => 'section_type',
                        'value'         => old('section_type'),
                        'options'       => [
                            'off_set_visit' => 'Off-Site Event',
                            'rep_visit'     => 'Rep Visit'
                        ],
                        'withIndex'     => true,
                        'placeholder'   => '',
                        'label'         => __('Section'),
                        'attrs'         => [
                            'required'
                        ]
                    ])
                        @error('section_type')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    @endcomponent
                    {{--[/section-type]--}}
                    {{--[start-time]--}}
                    <div class="row">
                        <div class="col-9">
                            @component('components.forms.input', [
                                'id'            => 'start-time',
                                'name'          => 'start_time',
                                'value'         => old('start_time'),
                                'placeholder'   => '00:00',
                                'label'         => __('Start Time')
                            ])
                                @error('start_time')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            @endcomponent
                        </div>
                        <div class="col-3">
                            @component('components.forms.select', [
                                'id'            => 'start-time-meridiem',
                                'name'          => 'start_time_meridiem',
                                'options'       => App\Models\System\Office::MERIDIUM_TYPES,
                                'value'         => old('start_time_meridiem'),
                                'placeholder'   => '',
                                'withIndex'     => true
                            ])
                                @error('start_hour_meridiem')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            @endcomponent
                        </div>
                    </div>
                    {{--[/start-time]--}}
                    {{--[end-time]--}}
                    <div class="row">
                        <div class="col-9">
                            @component('components.forms.input', [
                                'id'            => 'end-time',
                                'name'          => 'end_time',
                                'value'         => old('end_time'),
                                'placeholder'   => '00:00',
                                'label'         => __('End Time')
                            ])
                                @error('end_time')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            @endcomponent
                        </div>
                        <div class="col-3">
                            @component('components.forms.select', [
                                'id'            => 'end-time-meridiem',
                                'name'          => 'end_time_meridiem',
                                'options'       => App\Models\System\Office::MERIDIUM_TYPES,
                                'value'         => old('end_time_meridiem'),
                                'placeholder'   => '',
                                'withIndex'     => true
                            ])
                                @error('end_time_meridiem')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            @endcomponent
                        </div>
                    </div>
                    {{--[/end-time]--}}
                    {{--[repeats]--}}

                    {{--[/repeats]--}}
                @endcomponent
            </div>
        </div>
    </div>
@endcomponent
{{--[/modal]--}}
<script type="text/javascript">
<!--
    jQuery(document).ready(function($){
        let modal = $('#office-settings-recurring-appointments-modal');
        let addRecurringAptBtn = $('#add-recurring-appointment-btn');
    });
//--
</script>
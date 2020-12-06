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
            ],
            'attrs'     => [
                'data-toggle'   => 'modal',
                'data-target'   => '#office-settings-recurring-appointments-modal'
            ]
        ])
            {{ __('Add Recurring Appointment') }}
        @endcomponent
    </div>
    <div class="card-body">
        @if($recurringAppointments = $office->calendarEvents()->where('recurring', App\Models\System\CalendarEvent::RECURRING)->paginate(25))
            @if($recurringAppointments->count() !== 0)
                @component('components.elements.table_data', [
                    'id'        => 'office-settings-recurring-appointments-table',
                    'headers'    => [
                        'Type',
                        'Section',
                        'Start Time',
                        'End Time',
                        'Repeats',
                        '',
                        'Delete'
                    ]
                ])
                    @foreach($recurringAppointments as $recurringAppointment)
                        <tr>
                            <td>{{ $recurringAppointment->title }}</td>
                            <td>
                                {{ $recurringAppointment->getMetaField('type') }}
                            </td>
                            <td>
                                {{ Carbon\Carbon::parse($recurringAppointment->start_at)->format('h:i') }}
                                {{ $recurringAppointment->getMetaField('start_time_meridiem', 'am') }}
                            </td>
                            <td>
                                {{ Carbon\Carbon::parse($recurringAppointment->ends_at)->format('h:i') }}
                                {{ $recurringAppointment->getMetaField('end_time_meridiem', 'pm') }}
                            </td>
                            <td>
                                {{ $recurringAppointment->getMetaField('repeat_type') }}
                            </td>
                            <td></td>
                            <td>

                            </td>
                        </tr>
                    @endforeach
                @endcomponent
            @else
                <p class="card-text text-center">{{ __('No recuring appointments found.') }}</p>
            @endif
        @else
            <p class="card-text text-center">{{ __('No recuring appointments found.') }}</p>
        @endif
    </div>
@endcomponent
{{--[modal]--}}
@component('components.bootstrap.modal', [
    'id'        => 'office-settings-recurring-appointments-modal',
    'title'     => 'Add Recurring Appointment',
    'size'      => 'modal-lg',
    'options'   => [
        'backdrop'  => true,
        'keyboard'  => true,
        'focus'     => true,
        'show'      => (request()->input('modal') == 'true')? true: false
    ],
    'buttons'   =>
        '<button type="button" class="btn btn-secondary" data-dismiss="modal">'.__('Cancel').'</button>'.
        '<button type="button" id="office-settings-recurring-appointments-modal-submit-btn" class="btn btn-primary">'
        .__('Save').'</button>'
])
    <div class="modal-body">
        <div class="row justify-content-center">
            <div class="col-md-10">
                @component('components.forms.form', [
                    'id'        => 'office-settings-recurring-appointments-modal-form',
                    'action'    => route('office.settings.create.recurring.appointment'),
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
                    <div class="row">
                        {{--[start-time]--}}
                        <div class="col-6">
                            @component('components.forms.timepicker', [
                                'id'            => 'start-time',
                                'name'          => 'start_time',
                                'value'         => old('start_time'),
                                'placeholder'   => '12:00AM',
                                'label'         => __('Start Time')
                            ])
                                @error('start_time')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            @endcomponent
                        </div>
                        {{--[/start-time]--}}
                        {{--[end-time]--}}
                        <div class="col-6">
                            @component('components.forms.timepicker', [
                                'id'            => 'end-time',
                                'name'          => 'end_time',
                                'value'         => old('end_time'),
                                'placeholder'   => '1:00PM',
                                'label'         => __('End Time')
                            ])
                                @error('end_time')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            @endcomponent
                        </div>
                        {{--[/end-time]--}}
                    </div>
                    {{--[repeats]--}}
                    <div class="row">
                        <div class="col-12 col-md-6">
                            @component('components.forms.select', [
                                'id'        => 'repeat-type',
                                'name'      => 'repeat_type',
                                'options'   => [
                                    'monthly'   => 'Monthly',
                                    'weekly'    => 'Weekly'
                                ],
                                'value'     => old('repeat_type'),
                                'label'     =>  __('Repeat'),
                                'withIndex' => true,
                            ])
                                @error('repeat_type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            @endcomponent
                        </div>
                        <div class="col-12 col-md-6">
                            <div id="additiona-form-options" class="hidden">
                                @component('components.forms.select', [
                                    'id'                => 'repeat-day',
                                    'name'              => 'repeat_day',
                                    'value'             => old('repeat_day'),
                                    'options'           => App\Models\System\CalendarEvent::DAYS,
                                    'withIndex'         => false,
                                    'label'             => __('Day')
                                ])
                                    @error('repeat_day')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                                @component('components.forms.datepicker', [
                                    'id'                => 'repeat-month-day',
                                    'name'              => 'repeat-month-day',
                                    'value'             => old('month_day'),
                                    'label'             => __('Day')
                                ])
                                    @error('repeat-month-day')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                            </div>
                        </div>
                    </div>
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
        let form = modal.find('#office-settings-recurring-appointments-modal-form');
        let submitBtn = modal.find('#office-settings-recurring-appointments-modal-submit-btn');
        let addRecurringAptBtn = $('#add-recurring-appointment-btn');
        let additionalOptions = $('#additiona-form-options');
        let repeatType = $('#repeat-type');

        repeatType.on('change', function(i){
            let value = $(this).val();
            console.log(value);

            if(value == 'monthly') {
                additionalOptions.removeClass('hidden');
                additionalOptions.find('#repeat-month-day').closest('.form-group').removeClass('hidden');
                additionalOptions.find('#repeat-day').closest('.form-group').addClass('hidden');
            } else {
                additionalOptions.removeClass('hidden');
                additionalOptions.find('#repeat-month-day').closest('.form-group').addClass('hidden');
                additionalOptions.find('#repeat-day').closest('.form-group').removeClass('hidden');
            }
        });

        submitBtn.on('click touchend', function(e){
            form.submit();
        });
    });
//--
</script>
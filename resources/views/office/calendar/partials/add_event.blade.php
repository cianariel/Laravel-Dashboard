{{-- Stored in /resources/views/office/calendar/partials/add_event.blade.php --}}
@component('components.bootstrap.modal', [
    'id'        => 'office-add-calendar-events-modal',
    'title'     => __('Add Appointent'),
    'size'      => 'modal-lg',
    'buttons'   => [
        [
            'name'      => 'close-btn',
            'value'     => '',
            'label'     => __('Cancel'),
            'attrs'     => [
                'data-toggle'   => 'modal',
                'data-target'   => '#ffice-add-calendar-events-modal'
            ],
            'classes'   => [
                'btn',
                'btn-secondary'
            ]
        ],
        [
            'id'        => 'save-btn',
            'name'      => 'save-btn',
            'value'     => '',
            'label'     => __('Save Event'),
            'classes'   => [
                'btn',
                'btn-primary'
            ]
        ]
    ],
    'options'       => [
        'backdrop'  => true,
        'keyboard'  => true,
        'focus'     => true,
        'show'      => (request()->has('errors'))? true : false
    ]
])
    <div class="p-3">
        @if(request()->has('dialog_message'))
            <div class="alert alert-danger" role="alert">
                {{ request()->input('dialog_message') }}
            </div>
            <div class="mb-3"></div>
        @endif
        @component('components.forms.form', [
            'id'        => 'office-add-calendar-events-modal-form',
            'method'    => 'POST',
            'action'    => route('office.calendar.store'),
            'classes'   => [
                'no-form-update-handler'
            ]
        ])
            {{--[username]--}}
            <h5 class="card-text font-sm-size">{{ __('Rep') }}</h5>
            @component('components.forms.select', [
                'id'            => 'username',
                'name'          => 'username',
                'value'         => old('username'),
                'options'       => $repUsers,
                'withIndex'     => true,
                'placeholder'   => ''
            ])
                @error('username')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            @endcomponent
            {{--[/username]--}}
            {{--[title]--}}
            <h5 class="card-text font-sm-size">{{ __('Title') }}</h5>
            @component('components.forms.input', [
                'id'            => 'title',
                'name'          => 'title',
                'value'         => old('title'),
                'placeholder'   => 'EX: Rep Appointent',
            ])
                @error('title')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            @endcomponent
            {{--[/title]--}}
            <div class="row">
                <div class="col-12 col-md-6">
                    {{--[section]--}}
                    <h5 class="card-text font-sm-size">{{ __('Section') }}</h5>
                    @component('components.forms.select', [
                        'id'            => 'section',
                        'name'          => 'section',
                        'value'         => old('section'),
                        'options'       => [
                            App\Models\System\CalendarEvent::OFF_SET_VISIT_TYPE  => __('Off Set Visit'),
                            App\Models\System\CalendarEvent::REP_VISIT_TYPE      => __('Rep Visit')
                        ],
                        'withIndex'     => true,
                        'placeholder'   => '',
                    ])
                        @error('section')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    @endcomponent
                    {{--[/section]--}}
                </div>
                {{--[date]--}}
                <div class="col-12 col-md-6">
                    <h5 class="card-text font-sm-size">{{ __('Date') }}</h5>
                    @component('components.forms.datepicker', [
                        'id'            => 'date',
                        'name'          => 'date',
                        'value'         => old('date'),
                        'placeholder'   => 'MM/DD/YY',
                    ])
                        @error('date')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    @endcomponent
                </div>
                {{--[date]--}}
            </div>
            <div class="row">
                {{--[start-time]--}}
                <div class="col-12 col-md-6">
                    <h5 class="card-text font-sm-size">{{ __('Start Time') }}</h5>
                    @component('components.forms.timepicker', [
                        'id'            => 'start-time',
                        'name'          => 'start_time',
                        'value'         => old('start_time'),
                        'placeholder'   => '12:00AM',
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
                <div class="col-12 col-md-6">
                    <h5 class="card-text font-sm-size">{{ __('End Time') }}</h5>
                    @component('components.forms.timepicker', [
                        'id'            => 'end-time',
                        'name'          => 'end_time',
                        'value'         => old('end_time'),
                        'placeholder'   => '1:00PM',
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
            <div class="row">
                <div class="col-12 col-md-6">
                    {{--[recurring]--}}
                    <h5 class="card-text font-sm-size">{{ __('Recurring?') }}</h5>
                    @component('components.forms.select', [
                        'id'            => 'recurring',
                        'name'          => 'recurring',
                        'value'         => old('recurring'),
                        'options'       => [
                            App\Models\System\CalendarEvent::NOT_RECURRING => __('No'),
                            App\Models\System\CalendarEvent::RECURRING     => __('Yes')
                        ],
                        'withIndex'     => true,
                        'placeholder'   => '',
                    ])
                        @error('recurring')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    @endcomponent
                    {{--[/recurring]--}}
                </div>
                <div class="col-12 col-md-6">
                    <h5 class="card-text font-sm-size">{{ __('Repeat Type') }}</h5>
                    {{--[repeat_type]--}}
                    @component('components.forms.select', [
                        'id'                => 'repeat_type',
                        'name'              => 'repeat_type',
                        'value'             => old('repeat_type'),
                        'options'           => [
                            App\Models\System\CalendarEvent::REPEAT_WEEKLY      => __('Weekly'),
                            App\Models\System\CalendarEvent::REPEAT_MONTHLY     => __('Monthly')
                        ],
                        'withIndex'     => true,
                        'placeholder'   => '',
                        'help_text'     => __('Repeat by'),
                        'help_text'     => '(required if recurring appointment)'
                    ])
                        @error('repeat_type')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    @endcomponent
                    {{--[/repeat_type]--}}
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    {{--[notes]--}}
                    <h5 class="card-text">{{ __('Notes') }}</h5>
                    @component('components.forms.textarea', [
                        'id'            => 'notes',
                        'name'          => 'notes',
                        'value'         => old('notes'),
                        'placeholder'   => 'No HTML. (Max: 250 Characters)',
                        'help_text'     => '(optional)'
                    ])
                        @error('notes')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    @endcomponent
                    {{--[/notes]--}}
                </div>
            </div>
        @endcomponent
    </div>
@endcomponent
<script type="text/javascript">
<!--
    jQuery(document).ready(function($){
        let modal = $('#office-add-calendar-events-modal');
        let form = modal.find('#office-add-calendar-events-modal-form');
        let btn = modal.find('button');

        btn.on('click touchend', function(e){
            e.preventDefault();

            form.submit();
        });
    });
//-->
</script>
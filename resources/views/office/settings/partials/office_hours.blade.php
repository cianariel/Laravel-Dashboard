{{-- Stored in /resources/views/office/settings/partials/office_hours.blade.php --}}
@component('components.bootstrap.card', [
    'id'    => 'office-settings-holiday-card',
])
    <div class="card-header">
        {{ __('Office Hours') }}
    </div>
    <div class="card-body">
        @component('components.forms.form', [
            'id'        => 'office-settings-office-hours-form',
            'action'    => route('office.settings.update.general.section', 'office_hours'),
            'method'    => 'PUT',
            'confirmed' => true,
        ])
            <h6 class="card-text text-center">{{ __('Let reps know when your office is open') }}</h6>
            <div class="row">
                <div class="col-12">
                    @component('components.elements.table', [
                        'headers'   => [
                            'Day',
                            'Hour',
                            '',
                            'Hour',
                            ''
                        ],
                        'classes' => [
                            'mt-3',
                            'table-striped'
                        ]
                    ])
                        {{--[monday]--}}
                        <tr>
                            <td width="400">
                                @component('components.forms.toggler', [
                                    'id'        => 'days-monday-enabled',
                                    'name'      => 'days[monday][enabled]',
                                    'value'     => 'on',
                                    'selected'  => (old('days.monday.enabled') == 'on' || $office->getMetaField('office_hours->monday->enabled') == 'on')? true : false,
                                    'label'     => __('Monday'),
                                    'size'      => '2x',
                                    'classes'   => [
                                        'days-toggler',
                                    ]
                                ])

                                    @error('days.monday.enabled')
                                        <span class="font-xxs-size fg-red">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-6">
                                        @component('components.forms.timepicker', [
                                            'id'            => 'days.monday.start_hour',
                                            'name'          => 'days[monday][start_hour]',
                                            'value'         => old('days.monday.start_hour')?? $office->getMetaField('office_hours->monday->start_hour'),
                                            'placeholder'   => '0:00',
                                            'time_format'   => 'g:i',
                                        ])
                                            @error('days.monday.start_hour')
                                                <span class="font-xxs-size fg-red">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        @endcomponent
                                    </div>
                                    <div class="col-6">
                                        @component('components.forms.select', [
                                            'id'            => 'days-monday-start_hour_meridiem',
                                            'name'          => 'days[monday][start_hour_meridiem]',
                                            'value'         => old('days.monday.start_hour_meridiem')?? $office->getMetaField('office_hours->monday->start_hour_meridiem'),
                                            'placeholder'   => '',
                                            'options'       => App\Models\System\Office::MERIDIUM_TYPES,
                                            'withIndex'     => true
                                        ])
                                            @error('days.monday.start_hour_meridiem')
                                                <span class="font-xxs-size fg-red">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        @endcomponent
                                    </div>
                                </div>
                            </td>
                            <td>-</td>
                           <td>
                                <div class="row">
                                    <div class="col-6">
                                        @component('components.forms.timepicker', [
                                            'id'            => 'days-monday-end_hour',
                                            'name'          => 'days[monday][end_hour]',
                                            'value'         => old('days.monday.end_hour')?? $office->getMetaField('office_hours->monday->end_hour'),
                                            'placeholder'   => '0:00',
                                            'time_format'   => 'g:i',
                                        ])
                                            @error('days.monday.end_hour')
                                                <span class="font-xxs-size fg-red">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        @endcomponent
                                    </div>
                                    <div class="col-6">
                                        @component('components.forms.select', [
                                            'id'            => 'days-monday-end_hour_meridiem',
                                            'name'          => 'days[monday][end_hour_meridiem]',
                                            'value'         => old('days.monday.end_hour_meridiem')?? $office->getMetaField('office_hours->monday->end_hour_meridiem'),
                                            'placeholder'   => '',
                                            'options'       => App\Models\System\Office::MERIDIUM_TYPES,
                                            'withIndex'     => true
                                        ])
                                            @error('days.monday.end_hour_meridiem')
                                                <span class="font-xxs-size fg-red">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        @endcomponent
                                    </div>
                                </div>
                            </td>
                            <td></td>
                        </tr>
                        {{--[/monday]--}}
                        {{--[tuesday]--}}
                        <tr>
                            <td>
                                @component('components.forms.toggler', [
                                    'id'        => 'days-tuesday-enabled',
                                    'name'      => 'days[tuesday][enabled]',
                                    'value'     => 'on',
                                    'selected'  => (old('days.tuesday.enabled') == 'on' || $office->getMetaField('office_hours->tuesday->enabled') == 'on')? true : false,
                                    'label'     => __('Tuesday'),
                                    'size'      => '2x',
                                    'classes'   => [
                                        'days-toggler',
                                    ]
                                ])
                                    @error('days.tuesday.enabled')
                                        <span class="font-xxs-size fg-red">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-6">
                                        @component('components.forms.timepicker', [
                                            'id'            => 'days-tuesday-start_hour',
                                            'name'          => 'days[tuesday][start_hour]',
                                            'value'         => old('days.tuesday.start_hour')?? $office->getMetaField('office_hours->tuesday->start_hour'),
                                            'placeholder'   => '0:00',
                                            'time_format'   => 'g:i',
                                        ])
                                            @error('days.tuesday.start_hour')
                                                <span class="font-xxs-size fg-red">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        @endcomponent
                                    </div>
                                    <div class="col-6">
                                        @component('components.forms.select', [
                                            'id'            => 'days-tuesday-start_hour_meridiem',
                                            'name'          => 'days[tuesday][start_hour_meridiem]',
                                            'value'         => old('days.tuesday.start_hour_meridiem')?? $office->getMetaField('office_hours->tuesday->start_hour_meridiem'),
                                            'placeholder'   => '',
                                            'options'       => App\Models\System\Office::MERIDIUM_TYPES,
                                            'withIndex'     => true
                                        ])
                                            @error('days.tuesday.start_hour_meridiem')
                                                <span class="font-xxs-size fg-red">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        @endcomponent
                                    </div>
                                </div>
                            </td>
                            <td>-</td>
                            <td>
                                <div class="row">
                                    <div class="col-6">
                                        @component('components.forms.timepicker', [
                                            'id'            => 'days-tuesday-end_hour',
                                            'name'          => 'days[tuesday][end_hour]',
                                            'value'         => old('days.tuesday.end_hour')?? $office->getMetaField('office_hours->tuesday->end_hour'),
                                            'placeholder'   => '0:00',
                                            'time_format'   => 'g:i',
                                        ])
                                            @error('days.tuesday.end_hour')
                                                <span class="font-xxs-size fg-red">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        @endcomponent
                                    </div>
                                    <div class="col-6">
                                        @component('components.forms.select', [
                                            'id'            => 'days-tuesday-end_hour_meridiem',
                                            'name'          => 'days[tuesday][end_hour_meridiem]',
                                            'value'         => old('days.tuesday.end_hour_meridiem')?? $office->getMetaField('office_hours->tuesday->end_hour_meridiem'),
                                            'placeholder'   => '',
                                            'options'       => App\Models\System\Office::MERIDIUM_TYPES,
                                            'withIndex'     => true
                                        ])
                                            @error('days.tuesday.end_hour_meridiem')
                                                <span class="font-xxs-size fg-red">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        @endcomponent
                                    </div>
                                </div>
                            </td>
                            <td></td>
                        </tr>
                        {{--[/tuesday]--}}
                        {{--[wednesday]--}}
                        <tr>
                            <td>
                                @component('components.forms.toggler', [
                                    'id'        => 'days-wednesday-enabled',
                                    'name'      => 'days[wednesday][enabled]',
                                    'value'     => 'on',
                                    'selected'  => (old('days.wednesday.enabled') == 'on' || $office->getMetaField('office_hours->wednesday->enabled') == 'on')? true : false,
                                    'label'     => __('Wednesday'),
                                    'size'      => '2x',
                                    'classes'   => [
                                        'days-toggler',
                                    ]
                                ])
                                    @error('days.wednesday.enabled')
                                        <span class="font-xxs-size fg-red">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-6">
                                        @component('components.forms.timepicker', [
                                            'id'            => 'days-wednesday-start_hour',
                                            'name'          => 'days[wednesday][start_hour]',
                                            'value'         => old('days.wednesday.start_hour')?? $office->getMetaField('office_hours->wednesday->start_hour'),
                                            'placeholder'   => '0:00',
                                            'time_format'   => 'g:i',
                                        ])
                                            @error('days.wednesday.start_hour')
                                                <span class="font-xxs-size fg-red">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        @endcomponent
                                    </div>
                                    <div class="col-6">
                                        @component('components.forms.select', [
                                            'id'            => 'days-wednesday-start_hour_meridiem',
                                            'name'          => 'days[wednesday][start_hour_meridiem]',
                                            'value'         => old('days.wednesday.start_hour_meridiem')?? $office->getMetaField('office_hours->wednesday->start_hour_meridiem'),
                                            'placeholder'   => '',
                                            'options'       => App\Models\System\Office::MERIDIUM_TYPES,
                                            'withIndex'     => true
                                        ])
                                            @error('days.wednesday.start_hour_meridiem')
                                                <span class="font-xxs-size fg-red">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        @endcomponent
                                    </div>
                                </div>
                            </td>
                            <td>-</td>
                            <td>
                                <div class="row">
                                    <div class="col-6">
                                        @component('components.forms.timepicker', [
                                            'id'            => 'days-wednesday-end_hour',
                                            'name'          => 'days[wednesday][end_hour]',
                                            'value'         => old('days.wednesday.end_hour')?? $office->getMetaField('office_hours->wednesday->end_hour'),
                                            'placeholder'   => '0:00',
                                            'time_format'   => 'g:i',
                                        ])
                                            @error('days.wednesday.end_hour')
                                                <span class="font-xxs-size fg-red">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        @endcomponent
                                    </div>
                                    <div class="col-6">
                                        @component('components.forms.select', [
                                            'id'            => 'days-wednesday-end_hour_meridiem',
                                            'name'          => 'days[wednesday][end_hour_meridiem]',
                                            'value'         => old('days.wednesday.end_hour_meridiem')?? $office->getMetaField('office_hours->wednesday->end_hour_meridiem'),
                                            'placeholder'   => '',
                                            'options'       => App\Models\System\Office::MERIDIUM_TYPES,
                                            'withIndex'     => true
                                        ])
                                            @error('days.wednesday.end_hour_meridiem')
                                                <span class="font-xxs-size fg-red">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        @endcomponent
                                    </div>
                                </div>
                            </td>
                            <td></td>
                        </tr>
                        {{--[/wednesday]--}}
                        {{--[thursday]--}}
                        <tr>
                            <td>
                                @component('components.forms.toggler', [
                                    'id'        => 'days-thursday-enabled',
                                    'name'      => 'days[thursday][enabled]',
                                    'value'     => 'on',
                                    'selected'  => (old('days.thursday.enabled') == 'on' || $office->getMetaField('office_hours->thursday->enabled') == 'on')? true : false,
                                    'label'     => __('Thursday'),
                                    'size'      => '2x',
                                    'classes'   => [
                                        'days-toggler',
                                    ]
                                ])
                                    @error('days.thursday.enabled')
                                        <span class="font-xxs-size fg-red">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-6">
                                        @component('components.forms.timepicker', [
                                            'id'            => 'days-thursday-start_hour',
                                            'name'          => 'days[thursday][start_hour]',
                                            'value'         => old('days.thursday.start_hour')?? $office->getMetaField('office_hours->thursday->start_hour'),
                                            'placeholder'   => '0:00',
                                            'time_format'   => 'g:i',
                                        ])
                                            @error('days.thursday.start_hour')
                                                <span class="font-xxs-size fg-red">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        @endcomponent
                                    </div>
                                    <div class="col-6">
                                        @component('components.forms.select', [
                                            'id'            => 'days-thursday-start_hour_meridiem',
                                            'name'          => 'days[thursday][start_hour_meridiem]',
                                            'value'         => old('days.thursday.start_hour_meridiem')?? $office->getMetaField('office_hours->thursday->start_hour_meridiem'),
                                            'placeholder'   => '',
                                            'options'       => App\Models\System\Office::MERIDIUM_TYPES,
                                            'withIndex'     => true
                                        ])
                                            @error('days.thursday.start_hour_meridiem')
                                                <span class="font-xxs-size fg-red">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        @endcomponent
                                    </div>
                                </div>
                            </td>
                            <td>-</td>
                            <td>
                                <div class="row">
                                    <div class="col-6">
                                        @component('components.forms.timepicker', [
                                            'id'            => 'days-thursday-end_hour',
                                            'name'          => 'days[thursday][end_hour]',
                                            'value'         => old('days.thursday.end_hour')?? $office->getMetaField('office_hours->thursday->end_hour'),
                                            'placeholder'   => '0:00',
                                            'time_format'   => 'g:i',
                                        ])
                                            @error('days.thursday.end_hour')
                                                <span class="font-xxs-size fg-red">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        @endcomponent
                                    </div>
                                    <div class="col-6">
                                        @component('components.forms.select', [
                                            'id'            => 'days-thursday-end_hour_meridiem',
                                            'name'          => 'days[thursday][end_hour_meridiem]',
                                            'value'         => old('days.thursday.end_hour_meridiem')?? $office->getMetaField('office_hours->thursday->end_hour_meridiem'),
                                            'placeholder'   => '',
                                            'options'       => App\Models\System\Office::MERIDIUM_TYPES,
                                            'withIndex'     => true
                                        ])
                                            @error('days.thursday.end_hour_meridiem')
                                                <span class="font-xxs-size fg-red">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        @endcomponent
                                    </div>
                                </div>
                            </td>
                            <td></td>
                        </tr>
                        {{--[/thursday]--}}
                        {{--[friday]--}}
                        <tr>
                            <td>
                                @component('components.forms.toggler', [
                                    'id'        => 'days-friday-enabled',
                                    'name'      => 'days[friday][enabled]',
                                    'value'     => 'on',
                                    'selected'  => (old('days.friday.enabled') == 'on' || $office->getMetaField('office_hours->friday->enabled') == 'on')? true : false,
                                    'label'     => __('Friday'),
                                    'size'      => '2x',
                                    'classes'   => [
                                        'days-toggler',
                                    ]
                                ])
                                    @error('days.friday.enabled')
                                        <span class="font-xxs-size fg-red">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-6">
                                        @component('components.forms.input', [
                                            'id'            => 'days-friday-start_hour',
                                            'name'          => 'days[friday][start_hour]',
                                            'value'         => old('days.friday.start_hour')?? $office->getMetaField('office_hours->friday->start_hour'),
                                            'placeholder'   => '0:00',
                                        ])
                                            @error('days.friday.start_hour')
                                                <span class="font-xxs-size fg-red">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        @endcomponent
                                    </div>
                                    <div class="col-6">
                                        @component('components.forms.select', [
                                            'id'            => 'days-friday-start_hour_meridiem',
                                            'name'          => 'days[friday][start_hour_meridiem]',
                                            'value'         => old('days.friday.start_hour_meridiem')?? $office->getMetaField('office_hours->friday->start_hour_meridiem'),
                                            'placeholder'   => '',
                                            'options'       => App\Models\System\Office::MERIDIUM_TYPES,
                                            'withIndex'     => true
                                        ])
                                            @error('days.friday.start_hour_meridiem')
                                                <span class="font-xxs-size fg-red">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        @endcomponent
                                    </div>
                                </div>
                            </td>
                            <td>-</td>
                            <td>
                                <div class="row">
                                    <div class="col-6">
                                        @component('components.forms.timepicker', [
                                            'id'            => 'days-friday-end_hour',
                                            'name'          => 'days[friday][end_hour]',
                                            'value'         => old('days.friday.end_hour')?? $office->getMetaField('office_hours->friday->end_hour'),
                                            'placeholder'   => '0:00',
                                            'time_format'   => 'g:i',
                                        ])
                                            @error('days.friday.end_hour')
                                                <span class="font-xxs-size fg-red">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        @endcomponent
                                    </div>
                                    <div class="col-6">
                                        @component('components.forms.select', [
                                            'id'            => 'days-friday-end_hour_meridiem',
                                            'name'          => 'days[friday][end_hour_meridiem]',
                                            'value'         => old('days.friday.end_hour_meridiem')?? $office->getMetaField('office_hours->friday->end_hour_meridiem'),
                                            'placeholder'   => '',
                                            'options'       => App\Models\System\Office::MERIDIUM_TYPES,
                                            'withIndex'     => true
                                        ])
                                            @error('days.friday.end_hour_meridiem')
                                                <span class="font-xxs-size fg-red">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        @endcomponent
                                    </div>
                                </div>
                            </td>
                            <td></td>
                        </tr>
                        {{--[/friday]--}}
                        {{--[saturday]--}}
                        <tr>
                            <td>
                                @component('components.forms.toggler', [
                                    'id'        => 'days-saturday-enabled',
                                    'name'      => 'days[saturday][enabled]',
                                    'value'     => 'on',
                                    'selected'  => (old('days.saturday.enabled') == 'on' || $office->getMetaField('office_hours->saturday->enabled') == 'on')? true : false,
                                    'label'     => __('Saturday'),
                                    'size'      => '2x',
                                    'classes'   => [
                                        'days-toggler',
                                    ]
                                ])
                                    @error('days.saturday.enabled')
                                        <span class="font-xxs-size fg-red">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-6">
                                        @component('components.forms.timepicker', [
                                            'id'            => 'days-saturday-start_hour',
                                            'name'          => 'days[saturday][start_hour]',
                                            'value'         => old('days.saturday.start_hour')?? $office->getMetaField('office_hours->saturday->start_hour'),
                                            'placeholder'   => '0:00',
                                            'time_format'   => 'g:i',
                                        ])
                                            @error('days.saturday.start_hour')
                                                <span class="font-xxs-size fg-red">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        @endcomponent
                                    </div>
                                    <div class="col-6">
                                        @component('components.forms.select', [
                                            'id'            => 'days-saturday-start_hour_meridiem',
                                            'name'          => 'days[saturday][start_hour_meridiem]',
                                            'value'         => old('days.saturday.start_hour_meridiem')?? $office->getMetaField('office_hours->saturday->start_hour_meridiem'),
                                            'placeholder'   => '',
                                            'options'       => App\Models\System\Office::MERIDIUM_TYPES,
                                            'withIndex'     => true
                                        ])
                                            @error('days.saturday.start_hour_meridiem')
                                                <span class="font-xxs-size fg-red">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        @endcomponent
                                    </div>
                                </div>
                            </td>
                            <td>-</td>
                            <td>
                                <div class="row">
                                    <div class="col-6">
                                        @component('components.forms.timepicker', [
                                            'id'            => 'days-saturday-end_hour',
                                            'name'          => 'days[saturday][end_hour]',
                                            'value'         => old('days.saturday.end_hour')?? $office->getMetaField('office_hours->saturday->end_hour'),
                                            'placeholder'   => '0:00',
                                            'time_format'   => 'g:i',
                                        ])
                                            @error('days.saturday.end_hour')
                                                <span class="font-xxs-size fg-red">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        @endcomponent
                                    </div>
                                    <div class="col-6">
                                        @component('components.forms.select', [
                                            'id'            => 'days-saturday-end_hour_meridiem',
                                            'name'          => 'days[saturday][end_hour_meridiem]',
                                            'value'         => old('days.saturday.end_hour_meridiem')?? $office->getMetaField('office_hours->saturday->end_hour_meridiem'),
                                            'placeholder'   => '',
                                            'options'       => App\Models\System\Office::MERIDIUM_TYPES,
                                            'withIndex'     => true
                                        ])
                                            @error('days.saturday.end_hour_meridiem')
                                                <span class="font-xxs-size fg-red">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        @endcomponent
                                    </div>
                                </div>
                            </td>
                            <td></td>
                        </tr>
                        {{--[/saturday]--}}
                        {{--[sunday]--}}
                        <tr>
                            <td>
                                @component('components.forms.toggler', [
                                    'id'        => 'days-sunday-enabled',
                                    'name'      => 'days[sunday][enabled]',
                                    'value'     => 'on',
                                    'selected'  => (old('days.sunday.enabled') == 'on' || $office->getMetaField('office_hours->sunday->enabled') == 'on')? true : false,
                                    'label'     => __('Sunday'),
                                    'size'      => '2x',
                                    'classes'   => [
                                        'days-toggler',
                                    ]
                                ])
                                    @error('days.sunday.enabled')
                                        <span class="font-xxs-size fg-red">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                @endcomponent
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-6">
                                        @component('components.forms.timepicker', [
                                            'id'            => 'days-sunday-start_hour',
                                            'name'          => 'days[sunday][start_hour]',
                                            'value'         => old('days.sunday.start_hour')?? $office->getMetaField('office_hours->sunday->start_hour'),
                                            'placeholder'   => '0:00',
                                            'time_format'   => 'g:i',
                                        ])
                                            @error('days.sunday.start_hour')
                                                <span class="font-xxs-size fg-red">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        @endcomponent
                                    </div>
                                    <div class="col-6">
                                        @component('components.forms.select', [
                                            'id'            => 'days-sunday-start_hour_meridiem',
                                            'name'          => 'days[sunday][start_hour_meridiem]',
                                            'value'         => old('days.sunday.start_hour_meridiem')?? $office->getMetaField('office_hours->sunday->start_hour_meridiem'),
                                            'placeholder'   => '',
                                            'options'       => App\Models\System\Office::MERIDIUM_TYPES,
                                            'withIndex'     => true
                                        ])
                                            @error('days.sunday.start_hour_meridiem')
                                                <span class="font-xxs-size fg-red">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        @endcomponent
                                    </div>
                                </div>
                            </td>
                            <td>-</td>
                            <td>
                                <div class="row">
                                    <div class="col-6">
                                        @component('components.forms.timepicker', [
                                            'id'            => 'days-sunday-end_hour',
                                            'name'          => 'days[sunday][end_hour]',
                                            'value'         => old('days.sunday.end_hour')?? $office->getMetaField('office_hours->sunday->end_hour'),
                                            'placeholder'   => '0:00',
                                            'time_format'   => 'g:i',
                                        ])
                                            @error('days.sunday.end_hour')
                                                <span class="font-xxs-size fg-red">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        @endcomponent
                                    </div>
                                    <div class="col-6">
                                        @component('components.forms.select', [
                                            'id'            => 'days-sunday-end_hour_meridiem',
                                            'name'          => 'days[sunday][end_hour_meridiem]',
                                            'value'         => old('days.sunday.end_hour_meridiem')?? $office->getMetaField('office_hours->sunday->end_hour_meridiem'),
                                            'placeholder'   => '',
                                            'options'       => App\Models\System\Office::MERIDIUM_TYPES,
                                            'withIndex'     => true
                                        ])
                                            @error('days.sunday.end_hour_meridiem')
                                                <span class="font-xxs-size fg-red">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        @endcomponent
                                    </div>
                                </div>
                            </td>
                            <td></td>
                        </tr>
                        {{--[/sunday]--}}
                    @endcomponent
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-right">
                    @component('components.forms.button',[
                        'id'        => 'update-btn',
                        'type'      => 'submit',
                        'name'      => 'update-btn',
                        'label'     => __('Update'),
                        'classes'   => ['btn', 'btn-primary']
                    ])@endcomponent
                </div>
            </div>
        @endcomponent
    </div>
@endcomponent
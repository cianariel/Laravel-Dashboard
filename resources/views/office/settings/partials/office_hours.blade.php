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
                            <td>
                                @component('components.forms.toggler', [
                                    'id'        => 'days-monday-enabled',
                                    'name'      => 'days[monday][enabled]',
                                    'value'     => old('days.monday')?? $office->getMetaField('office_hours->monday->enabled'),
                                    'selected'  => (old('days.monday') == 'on' || $office->getMetaField('office_hours->monday->enabled') == 'on')? true : false,
                                    'label'     => __('Monday'),
                                    'size'      => '2x',
                                    'classes'   => [
                                        'days-toggler',
                                    ]
                                ])
                                    <span class="label-holder">{{ __('Closed') }}</span>
                                @endcomponent
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-6">
                                        @component('components.forms.input', [
                                            'id'            => 'days-monday-start_hour',
                                            'name'          => 'days[monday][start_hour]',
                                            'value'         => (old('days.monday.start_hour') == 'on' || $office->getMetaField('office_hours->monday->start_hour') == 'on')? true : false,
                                            'placeholder'   => '00:00',
                                        ])
                                            @error('days.monday.start_hour')
                                                <span class="invalid-feedback" role="alert">
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
                                            'options'       => ['am' => 'AM', 'pm' => 'PM'],
                                            'withIndex'     => true
                                        ])
                                            @error('days.monday.start_hour_meridiem')
                                                <span class="invalid-feedback" role="alert">
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
                                        @component('components.forms.input', [
                                            'id'            => 'days-monday-end_hour',
                                            'name'          => 'days[monday][end_hour]',
                                            'value'         => (old('days.monday.end_hour') == 'on' || $office->getMetaField('office_hours->monday->end_hour') == 'on')? true : false,
                                            'placeholder'   => '00:00',
                                        ])
                                            @error('days.monday.end_hour')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        @endcomponent
                                    </div>
                                    <div class="col-6">
                                        @component('components.forms.select', [
                                            'id'            => 'days-monday-end_meridiem',
                                            'name'          => 'days[monday][end_meridiem]',
                                            'value'         => old('days.monday.end_hour_meridiem')?? $office->getMetaField('office_hours->monday->end_hour_meridiem'),
                                            'placeholder'   => '',
                                            'options'       => ['am' => 'AM', 'pm' => 'PM'],
                                            'withIndex'     => true
                                        ])
                                            @error('days.monday.end_hour_meridiem')
                                                <span class="invalid-feedback" role="alert">
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
                                    'value'     => old('days.tuesday.enabled')?? $office->getMetaField('office_hours->tuesday->enabled'),
                                    'selected'  => (old('days.tuesday.enabled') == 'on' || $office->getMetaField('office_hours->tuesday->enabled') == 'on')? true : false,
                                    'label'     => __('Tuesday'),
                                    'size'      => '2x',
                                    'classes'   => [
                                        'days-toggler',
                                    ]
                                ])
                                    <span class="label-holder">{{ __('Closed') }}</span>
                                @endcomponent
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-6">
                                        @component('components.forms.input', [
                                            'id'            => 'days-tuesday-start_hour',
                                            'name'          => 'days[tuesday][start_hour]',
                                            'value'         => (old('days.tuesday.start_hour') == 'on' || $office->getMetaField('office_hours->tuesday->start_hour') == 'on')? true : false,
                                            'placeholder'   => '00:00',
                                        ])
                                            @error('days.tuesday.start_hour')
                                                <span class="invalid-feedback" role="alert">
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
                                            'options'       => ['am' => 'AM', 'pm' => 'PM'],
                                            'withIndex'     => true
                                        ])
                                            @error('days.tuesday.start_hour_meridiem')
                                                <span class="invalid-feedback" role="alert">
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
                                        @component('components.forms.input', [
                                            'id'            => 'days-tuesday-end_hour',
                                            'name'          => 'days[tuesday][end_hour]',
                                            'value'         => (old('days.tuesday.end_hour') == 'on' || $office->getMetaField('office_hours->tuesday->end_hour') == 'on')? true : false,
                                            'placeholder'   => '00:00',
                                        ])
                                            @error('days.tuesday.end_hour')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        @endcomponent
                                    </div>
                                    <div class="col-6">
                                        @component('components.forms.select', [
                                            'id'            => 'days-tuesday-end_meridiem',
                                            'name'          => 'days[tuesday][end_meridiem]',
                                            'value'         => old('days.tuesday.end_hour_meridiem')?? $office->getMetaField('office_hours->tuesday->end_hour_meridiem'),
                                            'placeholder'   => '',
                                            'options'       => ['am' => 'AM', 'pm' => 'PM'],
                                            'withIndex'     => true
                                        ])
                                            @error('days.tuesday.end_hour_meridiem')
                                                <span class="invalid-feedback" role="alert">
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
                    @endcomponent
                </div>
            </div>
        @endcomponent
    </div>
@endcomponent
<script type="text/javascript">
<!--
    jQuery(document).ready(function($){
        let form = $('#office-settings-office-hours-form');
    });
//-->
</script>
{{-- Stored in /resources/views/office/settings/partails/holidays.blade.php --}}
@component('components.bootstrap.card', [
    'id'    => 'office-settings-holiday-card',
])
    <div class="card-header">
        {{ __('Holidays') }}
    </div>
    <div class="card-body">
        @component('components.forms.form', [
            'id'        => 'office-settings-holiday-form',
            'action'    => route('office.settings.update.general.section', 'holidays'),
            'method'    => 'PUT',
            'confirmed' => true
        ])
            <h6 class="card-title mb-3 text-center">{{ __('Select holidays that your office is closed') }}</h6>
            <div class="row">
                <div class="col-12 col-md-6 mb-3 mb-md-0">
                    @component('components.forms.checkbox', [
                        'id'        => 'holidays-new_years',
                        'name'      => 'holidays[new_years]',
                        'value'     => 'on',
                        'label'     => __('New Year\'s Day'),
                        'checked'   => (old('holidays.new_years') == 'on' || $office->getMetaField('holidays_closed->new_years') == 'on')? true : false
                    ])
                        @error('holidays.new_years')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    @endcomponent
                    @component('components.forms.checkbox', [
                        'id'        => 'holidays-mlk_day',
                        'name'      => 'holidays[mlk_day]',
                        'value'     => 'on',
                        'label'     => __('MLK Day'),
                        'checked'   => (old('holidays.mlk_day') == 'on' || $office->getMetaField('holidays_closed->mlk_day') == 'on')? true : false
                    ])
                        @error('holidays.mlk_day')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    @endcomponent
                    @component('components.forms.checkbox', [
                        'id'        => 'holidays-presidents_day',
                        'name'      => 'holidays[presidents_day]',
                        'value'     => 'on',
                        'label'     => __('Presidents Day'),
                        'checked'   => (old('holidays.presidents_day') == 'on' || $office->getMetaField('holidays_closed->presidents_day') == 'on')? true : false
                    ])
                        @error('holidays.presidents_day')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    @endcomponent
                    @component('components.forms.checkbox', [
                        'id'        => 'holidays-good_friday',
                        'name'      => 'holidays[good_friday]',
                        'value'     => 'on',
                        'label'     => __('Good Friday'),
                        'checked'   => (old('holidays.good_friday') == 'on' || $office->getMetaField('holidays_closed->good_friday') == 'on')? true : false
                    ])
                        @error('holidays.good_friday')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    @endcomponent
                    @component('components.forms.checkbox', [
                        'id'        => 'holidays-memorial_day',
                        'name'      => 'holidays[memorial_day]',
                        'value'     => 'on',
                        'label'     => __('Memorial Day'),
                        'checked'   => (old('holidays.memorial_day') == 'on' || $office->getMetaField('holidays_closed->memorial_day') == 'on')? true : false
                    ])
                        @error('holidays.memorial_day')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    @endcomponent
                    @component('components.forms.checkbox', [
                        'id'        => 'holidays-independence_day',
                        'name'      => 'holidays[independence_day]',
                        'value'     => 'on',
                        'label'     => __('Independence Day'),
                        'checked'   => (old('holidays.independence_day') == 'on' || $office->getMetaField('holidays_closed->independence_day') == 'on')? true : false
                    ])
                        @error('holidays.independence_day')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    @endcomponent
                    @component('components.forms.checkbox', [
                        'id'        => 'holidays-labor_day',
                        'name'      => 'holidays[labor_day]',
                        'value'     => 'on',
                        'label'     => __('Labor Day'),
                        'checked'   => (old('holidays.labor_day') == 'on' || $office->getMetaField('holidays_closed->labor_day') == 'on')? true : false
                    ])
                        @error('holidays.labor_day')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    @endcomponent
                </div>
                <div class="col-12 col-md-6">
                    @component('components.forms.checkbox', [
                        'id'        => 'holidays-columbus_day',
                        'name'      => 'holidays[columbus_day]',
                        'value'     => 'on',
                        'label'     => __('Columbus Day'),
                        'checked'   => (old('holidays.columbus_day') == 'on' || $office->getMetaField('holidays_closed->new_years') == 'on')? true : false
                    ])
                        @error('holidays.columbus_day')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    @endcomponent
                    @component('components.forms.checkbox', [
                        'id'        => 'holidays-veterans_day',
                        'name'      => 'holidays[veterans_day]',
                        'value'     => 'on',
                        'label'     => __('Veterans Day'),
                        'checked'   => (old('holidays.veterans_day') == 'on' || $office->getMetaField('holidays_closed->veterans_day') == 'on')? true : false
                    ])
                        @error('holidays.veterans_day')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    @endcomponent
                    @component('components.forms.checkbox', [
                        'id'        => 'holidays-thanksgiving_day',
                        'name'      => 'holidays[thanksgiving_day]',
                        'value'     => 'on',
                        'label'     => __('Thanksgiving Day'),
                        'checked'   => (old('holidays.thanksgiving_day') == 'on' || $office->getMetaField('holidays_closed->thanksgiving_day') == 'on')? true : false
                    ])
                        @error('holidays.thanksgiving_day')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    @endcomponent
                    @component('components.forms.checkbox', [
                        'id'        => 'holidays-thanksgiving_friday',
                        'name'      => 'holidays[thanksgiving_friday]',
                        'value'     => 'on',
                        'label'     => __('Thanksgiving Friday'),
                        'checked'   => (old('holidays.thanksgiving_friday') == 'on' || $office->getMetaField('holidays_closed->thanksgiving_friday') == 'on')? true : false
                    ])
                        @error('holidays.thanksgiving_friday')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    @endcomponent
                    @component('components.forms.checkbox', [
                        'id'        => 'holidays-christmas_day',
                        'name'      => 'holidays[christmas_day]',
                        'value'     => 'on',
                        'label'     => __('Christmas Day'),
                        'checked'   => (old('holidays.christmas_day') == 'on' || $office->getMetaField('holidays_closed->christmas_day') == 'on')? true : false
                    ])
                        @error('holidays.christmas_day')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    @endcomponent
                    @component('components.forms.checkbox', [
                        'id'        => 'holidays-day_before_christmas',
                        'name'      => 'holidays[day_before_christmas]',
                        'value'     => 'on',
                        'label'     => __('Day Before Christmas'),
                        'checked'   => (old('holidays.day_before_christmas') == 'on' || $office->getMetaField('holidays_closed->day_before_christmas') == 'on')? true : false
                    ])
                        @error('holidays.day_before_christmas')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
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
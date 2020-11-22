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
            'action'    => route('office.settings.update.general.section', 'general'),
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
                        'checked'   => (old('holidays.new_years') == 'on')? true : false
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
                        'checked'   => (old('holidays.mlk_day') == 'on')? true : false
                    ])
                        @error('holidays.mlk_day')
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
                        'checked'   => (old('holidays.columbus_day') == 'on')? true : false
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
                        'checked'   => (old('holidays.veterans_day') == 'on')? true : false
                    ])
                        @error('holidays.veterans_day')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    @endcomponent
                </div>
            </div>
            <div class="row">
                <div class="col-12 offset-md-4">
                </div>
            </div>
        @endcomponent
    </div>
@endcomponent
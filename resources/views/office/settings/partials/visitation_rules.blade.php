{{-- Stored in /resources/views/office/settings/partials/visitation_rules.blade.php --}}
@component('components.bootstrap.card', [
    'id'    => 'office-settings-holiday-card',
])
    <div class="card-header">
        {{ __('Visitation Rules') }}
    </div>
    <div class="card-body">
        @component('components.forms.form', [
            'id'        => 'office-settings-holiday-form',
            'action'    => route('office.settings.update.general.section', 'visitation_rules'),
            'method'    => 'PUT',
            'confirmed' => true,
        ])
            <h4 class="card-title mb-3 text-center">
                {{ __('Would you like to approve reps before they can book appointments at your office?') }}
            </h4>
            <h6 class="card-title text-center">
                {{ __('Reps may request approval which you can accept or deny.') }}
                {{ __('You may also approve reps ahead of time') }}
            </h6>
            <div class="row mt-4">
                <div class="col-8 text-center">
                    {{ __('Require reps to be approved prior to booking with my office') }}
                </div>
                <div class="col-4">
                    @component('components.forms.toggler', [
                        'id'        => 'require_approve_appointments',
                        'name'      => 'require_approve_appointments',
                        'value'     => 'on',
                        'size'      => '2x',
                        'selected'  => (old('require_approve_appointments') == 'on' || $office->getMetaField('visitation_rules->require_approve_appointments') == 'on')? true : false
                    ])@endcomponent
                </div>
            </div>
            {{--[submit-btn]--}}
            <div class="form-group row">
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
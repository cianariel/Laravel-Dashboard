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
    </div>
@endcomponent
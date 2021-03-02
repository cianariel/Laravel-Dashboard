{{-- Stored in /resources/views/office/calendar/partials/add_calendar_event_link.blade.php --}}
@component('components.elements.link', [
    'id'        => 'office-dashboard-calendar-add-link',
    'classes'   => [
        'btn',
        'btn-primary',
        'pull-right',
        'mb-3'
    ],
    'href'          => 'office-add-calendar-events-modal',
    'attrs'         => [
        'data-toggle'   => 'modal',
        'data-target'   => '#office-add-calendar-events-modal',
    ]
])
    <i class="fas fa-calendar-plus"></i> {{ __('Add Appointent') }}
@endcomponent
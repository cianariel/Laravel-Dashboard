<div id="office-info-container">
    <div class="p-4 mt-2">
        <h5>{{ $office->label }}</h5>
        @php
            $location = $office->getMetaField('location', '');
        @endphp
        @if($location)
            <div>{{$location['address']. ", ". $location['city']. ", ". $location['state']. " " . $location['zipcode']}}</div>
        @endif
    </div>
    <div class="row">
        <div class="col-md-12">
            @php
                $visitation_rules = $office->getMetaField('visitation_rules');
            @endphp

            {{-- Active this button if office owner approved request --}}
            @if($office->pivot->status == 1 && !empty($visitation_rules['require_approve_appointments']) && $visitation_rules['require_approve_appointments'] == "on")
                <div class="text-center">
                    @component('components.elements.link', [
                        'id'        => 'user-reserve-appointment-link',
                        'classes'   => [
                            'btn',
                            'btn-primary'
                        ],
                        'href'          => 'user-reserve-appointment-modal',
                        'attrs'         => [
                            'data-toggle'   => 'modal',
                            'data-target'   => '#user-reserve-appointment-modal',
                            'data-id'       => $office->uuid
                        ]
                    ])
                        {{ __('Reserve Appointent') }}
                    @endcomponent
                </div>
                @include("user.calendar.partials.reserve_appointment_modal", ["office_uuid" => $office->uuid])
            @else
                <div class="alert alert-danger text-center">{{__("Because the office was disabled by owner, you can't reserve the appointment.")}}</div>
            @endif
        </div>
    </div>
</div>


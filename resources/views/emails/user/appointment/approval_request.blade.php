@component('mail::message')

{{ __('Hi') }} {{ $owner->first_name }},

You have recieved an approval request for an office, {{ $office->name  }} from {{ $repUser->first_name }}:

@component('mail::panel')
    {{ __('Please visit') }} <a href="#">{{ route('office.reps.show', ['rep' => $repUser->username]) }}</a> {{ __('to process the request') }}.
@endcomponent

Thank you,<br>
MD Rep Time
@endcomponent

@component('mail::message')
# {{ __('Hi') }} {{ $guestUser->first_name }},

{{ $office->label }} {{ __('invites you to join MD REP TIME, a website designed to simplify your day to day interactions with industry representatives') }} .
{{ __('We are revolutionizing the way reps interact with medical practices by providing you with a digital platform to manage meetings and communications') }}.

{{ __('Please click the link below and follow the registration process') }}:
@component('mail::button', ['url' => route('invitation.show', ['invite_code' => $guestUser->invite_code])])
{{ __('Click Here') }}
@endcomponent
{{ __('Thanks') }},<br>
{{ config('app.name') }}
@endcomponent

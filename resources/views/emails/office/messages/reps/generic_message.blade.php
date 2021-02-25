@component('mail::message')

# {{ Str::upper($to->username) }},

# You have recieved a message from {{ $office->label }}:

@component('mail::panel')
{{ $message->body }}
@endcomponent

{{ __('Please visit') }} <a href="#">{{ secure_url('/user/messages') }}</a> {{ __('to reply') }}.

Thank you,<br>
MD Rep Time
@endcomponent

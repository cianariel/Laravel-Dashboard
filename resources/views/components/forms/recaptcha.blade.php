{{-- Stored in /resources/views/components/forms/recaptcha.blade.php --}}
<div class="form-group row">
    @if(isset($label))<label for="{{ $name }}" class="col-sm-4 col-form-label text-md-right">{{ __($label) }}</label>@endif
    <div class="{{ isset($label)? 'col-sm-8' : 'col-sm-12' }}">
        @if(isset($options) && filled($options))
            {!! NoCaptcha::display($options) !!}
        @else
            {!! NoCaptcha::display() !!}
        @endif
        @if(isset($help_text))
            <small class="form-text text-muted">{{ $help_text }}</small>
        @endif
        {!! $slot !!}
    </div>
</div>
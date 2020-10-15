{{-- Stored in /resources/views/components/forms/password.blade.php --}}
<div class="form-group row">
    @if(isset($label))<label for="{{ $name }}" class="col-sm-4 col-form-label text-md-right">{{ __('Password') }}</label>@endif
    <div class="{{ isset($label)? 'col-sm-8' : 'col-sm-12' }}">
        <input id="{{ $id ?? $name }}" @if(isset($attrs) && filled($attrs)) @foreach($attrs as $attr => $attr_value) {{ $attr }}="{{ $attr_value }}" @endforeach @endif name="{{ $name }}" type="{{ $type ?? 'password' }}" class="form-control @if(isset($classes) && filled($classes) && count($classes) !=0) {{ implode(' ', $classes) }} @endif @error($error_name?? $name) is-invalid @enderror" placeholder="{{ $placeholder ?? 'Enter a password' }}" value="{{ $value }}" @if(isset($readonly) && $readonly === true) readonly @endif @if(isset($disabled)) disabled @endif autocomplete="new-password">
        @if(isset($help_text))
            <small class="form-text text-muted">{{ $help_text }}</small>
        @endif
        {{ $slot }}
    </div>
</div>
@if(!isset($not_confirmed))
{{--[confirm-password]--}}
<div class="form-group row">
    @if(isset($label))<label for="password-confirm" class="col-sm-4 col-form-label text-md-right">{{ __('Re-enter your password') }}</label>@endif
    <div class="{{ isset($label)? 'col-sm-8' : 'col-sm-12' }}">
        <input id="password-confirm" @if(isset($attrs) && filled($attrs)) @foreach($attrs as $attr => $attr_value) {{ $attr }}="{{ $attr_value }}" @endforeach @endif name="password_confirmation" type="password" class="form-control @if(isset($classes) && filled($classes) && count($classes) !=0) {{ implode(' ', $classes) }} @endif @error('password_confirmation') is-invalid @enderror" placeholder="{{ 'Re-enter your password' }}" value="" @if(isset($readonly) && $readonly === true) readonly @endif @if(isset($disabled)) disabled @endif autocomplete="new-password">
        @if(isset($help_text))
            <small class="form-text text-muted">{{ $help_text }}</small>
        @endif
        @error('password_confirmation')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
</div>
{{--[/confirm-password]--}}
@endif


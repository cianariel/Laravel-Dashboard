{{-- Stored in /resources/views/components/forms/password.blade.php --}}
<div class="form-group @if(empty($vertical)) row @endif">
    @if(isset($label))<label for="{{ $name }}" class="@if(empty($vertical)) col-sm-4 col-form-label text-md-right @endif">{{ __($label) }}</label>@endif
    <div class="@if(empty($vertical)) {{ isset($label)? 'col-sm-8' : 'col-sm-12' }} @endif">
        <div class=" password-holder">
            <input id="{{ $id ?? $name }}" @if(isset($attrs) && filled($attrs)) @foreach($attrs as $attr => $attr_value) {{ $attr }}="{{ $attr_value }}" @endforeach @endif name="{{ $name }}" type="{{ $type ?? 'password' }}" class="form-control @if(isset($classes) && filled($classes) && count($classes) !=0) {{ implode(' ', $classes) }} @endif @error($error_name?? $name) is-invalid @enderror" placeholder="{{ $placeholder ?? 'Enter a password' }}" value="{{ $value }}" @if(isset($readonly) && $readonly === true) readonly @endif @if(isset($disabled)) disabled @endif autocomplete="new-password">
            <span class="password-icon show-password"><i class="fa fa-eye"></i></span>
            <span class="password-icon hide-password" style="display: none"><i class="fa fa-eye-slash"></i></span>
            @if(isset($help_text))
                <small class="form-text text-muted">{{ $help_text }}</small>
            @endif
            {{ $slot }}
        </div>
    </div>
</div>
@if(!isset($not_confirmed))
{{--[confirm-password]--}}
<div class="form-group @if(empty($vertical)) row @endif">
    @if(isset($label))<label for="password-confirm" class="@if(empty($vertical)) col-sm-4 col-form-label text-md-right @endif">{{ __('Re-enter your password') }}</label>@endif
    <div class="@if(empty($vertical)) {{ isset($label)? 'col-sm-8' : 'col-sm-12' }} @endif">
        <div class=" password-holder">
            <input id="password-confirm" @if(isset($attrs) && filled($attrs)) @foreach($attrs as $attr => $attr_value) {{ $attr }}="{{ $attr_value }}" @endforeach @endif name="password_confirmation" type="password" class="form-control @if(isset($classes) && filled($classes) && count($classes) !=0) {{ implode(' ', $classes) }} @endif @error('password_confirmation') is-invalid @enderror" placeholder="{{ 'Re-enter your password' }}" value="" @if(isset($readonly) && $readonly === true) readonly @endif @if(isset($disabled)) disabled @endif autocomplete="new-password">
            <span class="password-icon show-password"><i class="fa fa-eye"></i></span>
            <span class="password-icon hide-password" style="display: none"><i class="fa fa-eye-slash"></i></span>

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
</div>

@component('components.elements.style', [])
    .password-holder{
        position: relative;
    }

    .password-icon{
        position: absolute;
        top: 6px;
        right: 10px;
        cursor: pointer;
    }
@endcomponent

@component('components.elements.script', [])
    $('document').ready(function(){
        $('.password-icon.show-password').click(function(){
            $(this).hide();
            $(this).parent().find('.password-icon.hide-password').show();
            $(this).prev().attr('type', 'text');
        })
        $('.password-icon.hide-password').click(function(){
            $(this).hide();
            $(this).parent().find('.password-icon.show-password').show();
            $(this).prev().prev().attr('type', 'password');
        })
    })
@endcomponent

{{--[/confirm-password]--}}
@endif


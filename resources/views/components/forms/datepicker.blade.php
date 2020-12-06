{{-- Stored in resources/views/components/forms/checkbox.blade.php --}}
<div class="form-group md-date-picker
    @if(isset($label)) row @endif
    @if(isset($classes) && filled($classes) && count($classes) !=0) {{ implode(' ', $classes) }} @endif
    {{ (isset($required_if) && filled($required_if))? ' md-required-if-input hidden' : '' }}"
    @if(isset($required_if) && filled($required_if)) data-required-if="{{ $required_if }}" @endif
    @if(isset($required_if_value) && filled($required_if_value)) data-required-if-value="{{ $required_if_value }}"@endif>
    @if(isset($label))<label for="{{ $name }}" class="col-sm-4 col-form-label text-md-right">{{ __($label) }}</label>@endif
    @if(isset($label))<div class="col-sm-8">@endif
        <input id="{{ $id?? $name }}"
            @if(isset($attrs) && filled($attrs))
                @foreach($attrs as $attr => $attr_value) {{ $attr }}="{{ $attr_value }}" @endforeach
            @endif
            name="{{ $name }}" type="text" @if(isset($inputmask))
            data-inputmask="{{ $inputmask }}"
            data-inputmask-clearmaskonlostfocus="true"
            data-inputmask-removemaskonsubmit="true" @endif
            class="form-control @if(isset($classes) && filled($classes) && count($classes) !=0) {{ implode(' ', $classes) }} @endif @error($error_name?? $name) is-invalid @enderror"
                placeholder="{{ $placeholder ?? '' }}" value="{{ $value?? '' }}"
                @if(isset($readonly) && $readonly === true) readonly @endif
                @if(isset($disabled) && $disabled === true) disabled @endif>
        @if(isset($help_text))
            <small class="form-text text-muted">{!! $help_text !!}</small>
        @endif
        {!! $slot !!}
    @if(isset($label))</div>@endif
</div>
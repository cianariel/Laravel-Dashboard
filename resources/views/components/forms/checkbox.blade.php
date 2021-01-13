{{-- Stored in resources/views/components/forms/checkbox.blade.php --}}
<div class="form-group @if(isset($label)) row @endif @if(isset($classes) && filled($classes) && count($classes) !=0) {{ implode(' ', $classes) }} @endif {{ (isset($required_if) && filled($required_if))? ' md-required-if-input hidden' : '' }}" @if(isset($required_if) && filled($required_if)) data-required-if="{{ $required_if }}" @endif @if(isset($required_if_value) && filled($required_if_value)) data-required-if-value="{{ $required_if_value }}"@endif>
    @if(isset($label))<div class="col-md-12">@endif
        <input id="{{ $name }}" type="checkbox" name="{{ $name }}" value="{{ $value?? '' }}" @if(isset($attrs) && filled($attrs)) @foreach($attrs as $attr => $attr_value) {{ $attr }}="{{ $attr_value }}" @endforeach @endif class="form-control @if(isset($classes) && filled($classes) && count($classes) !=0) {{ implode(' ', $classes) }} @endif @error($error_name?? $name) is-invalid @enderror" placeholder="{{ $placeholder ?? '' }}" @if(isset($readonly) && $readonly === true) readonly @endif @if(isset($checked) && $checked === true) checked @endif @if(isset($disabled)) disabled @endif>
        <label for="{{ $name }}" class="form-check-label">{{ $label ?? '' }}</label>
        {!! $slot !!}
    @if(isset($label))</div>@endif
</div>
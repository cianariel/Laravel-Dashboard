{{-- Stored in resources/views/components/forms/radio.blade.php --}}
<div class="form-group row{{ (isset($required_if) && filled($required_if))? ' md-required-if-input hidden' : '' }}" @if(isset($required_if) && filled($required_if)) data-required-if="{{ $required_if }}" @endif @if(isset($required_if_value) && filled($required_if_value)) data-required-if-value="{{ $required_if_value }}"@endif>
    <div class="col-md-8 offset-md-4">
        <div class="form-check">
            <input id="{{ $id ?? $name }}" @if(isset($attrs) && filled($attrs)) @foreach($attrs as $attr => $attr_value) {{ $attr }}="{{ $attr_value }}" @endforeach @endif name="{{ $name }}" type="radio" class="@if(isset($classes) && filled($classes) && count($classes) !=0) {{ implode(' ', $classes) }} @endif @error($error_name?? $name) is-invalid @enderror" placeholder="{{ $placeholder ?? '' }}" @if(isset($readonly) && $readonly === true) readonly @endif @if(isset($checked) && $checked === true) checked @endif @if(isset($disabled)) disabled @endif>
            <label for="{{ $id ?? $name }}" class="form-check-label">{{ $label ?? '' }}</label>
        </div>
        @if(isset($help_text))
        <small class="form-text text-muted">{{ $help_text }}</small>
        @endif
        {!! $slot !!}
    </div>
</div>
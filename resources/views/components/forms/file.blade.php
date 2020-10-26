{{-- Stored in /resources/views/components/forms/file.blade.php --}}
<div class="form-group row{{ (isset($required_if) && filled($required_if))? ' md-required-if-input hidden' : '' }}" @if(isset($required_if) && filled($required_if)) data-required-if="{{ $required_if }}" @endif @if(isset($required_if_value) && filled($required_if_value)) data-required-if-value="{{ $required_if_value }}"@endif>
    @if(isset($label))<label for="{{ $name }}" class="col-sm-4 col-form-label text-md-right">{{ __($label) }}</label>@endif
    <div class="{{ isset($label)? 'col-sm-8' : 'col-sm-12' }}">
        <div class="custom-file">
            <input id="{{ $id ?? $name }}" @if(isset($attrs) && filled($attrs)) @foreach($attrs as $attr => $attr_value) {{ $attr }}="{{ $attr_value }}" @endforeach @endif name="{{ $name }}" type="file" class="custom-file-input @if(isset($classes) && filled($classes) && count($classes) !=0) {{ implode(' ', $classes) }} @endif @error($error_name?? $name) is-invalid @enderror" @if(isset($accepts) && filled($accepts)) accept="{{ implode(',', $accepts)}}" @if(isset($multiple)) multiple @endif @endif @if(isset($readonly) && $readonly === true) readonly @endif>
            <label for="{{ $id ?? $name }}" class="custom-file-label">{{ __('Choose file') }}</label>
            {!! $slot !!}
        </div>
        @if(isset($help_text))
            <small class="form-text d-block text-muted">{{ $help_text }}</small>
        @endif
    </div>
</div>
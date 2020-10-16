{{-- Stored in resources/views/components/forms/editor.blade.php --}}
<div class="form-group row{{ (isset($required_if) && filled($required_if))? ' gb-required-if-input hidden' : '' }}" @if(isset($required_if) && filled($required_if)) data-required-if="{{ $required_if }}" @endif @if(isset($required_if_value) && filled($required_if_value)) data-required-if-value="{{ $required_if_value }}"@endif>
    @if(isset($label))<label for="{{ $name }}" class="col-sm-4 col-form-label text-md-right">{{ __($label) }}</label>@endif
    <div class="{{ isset($label)? 'col-sm-8' : 'col-sm-12' }}">
        <div class="md-editor">
            <div class="d-block mb-2 text-right">
                <button type="button" class="md-editor-toggler" data-enabled="true"><i class="icon fas fa-toggle-on"></i> <span class="text">{{ __('Hide Editor') }}</span></button>
            </div>
            <textarea id="md-editor-content-{{ $name }}" @if(isset($attrs) && filled($attrs)) @foreach($attrs as $attr => $attr_value) {{ $attr }}="{{ $attr_value }}" @endforeach @endif class="hidden form-control gb-editor-content mt-2 @error($error_name?? $name) is-invalid @enderror" name="{{ $name }}">{!! $value?? '' !!}</textarea>
            <div id="md-editor-{{ $name }}" class="editor"></div>
            {!! $slot !!}
        </div>
    </div>
</div>
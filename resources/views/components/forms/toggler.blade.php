{{-- Stored in /resources/views/components/forms/toggle.blade.php --}}
<div class="form-group row{{ (isset($required_if) && filled($required_if))? ' gb-required-if-input hidden' : '' }}" @if(isset($required_if) && filled($required_if)) data-required-if="{{ $required_if }}" @endif @if(isset($required_if_value) && filled($required_if_value)) data-required-if-value="{{ $required_if_value }}"@endif>
    @if(isset($label))<label for="{{ $name }}" class="col-sm-4 col-form-label text-md-right">{{ __($label) }}</label>@endif
    <div class="{{ isset($label)? 'col-sm-8' : 'col-sm-12' }}">
        <div class="md-toggler d-inline @if(isset($classes) && filled($classes) && count($classes) !=0) {{ implode(' ', $classes) }} @endif @error($error_name?? $name) is-invalid @enderror" data-id="{{ $id }}" data-value="{{ $value }}" data-selected="{{ $selected?  'true' : 'false' }}">
            @component('components.forms.hidden', ['id' => $id, 'name' => $name, 'value' => $value])@endcomponent
            @if($selected === true)
                <i class="icon fas fa-toggle-on"></i>
            @else
                <i class="icon fas fa-toggle-off"></i>
            @endif
        </div>
        @if(isset($help_text))
            <small class="form-text text-muted">{!! $help_text !!}</small>
        @endif
        {!! $slot !!}
    </div>
</div>
{{-- Stored in /resources/views/components/forms/button.blade.php --}}
@if(isset($type) && $type == 'submit' || $type == "dropdown-toggle")
    @if(filled($slot))
        {!! $slot !!}
    @endif
    <button @if(isset($id) && filled($id)) id="{{ $id }}" @endif type="{{ ($type == 'dropdown-toggle')? 'button' : $type?? 'button' }}" @if(isset($attrs) && filled($attrs)) @foreach($attrs as $attr => $attr_value) {{ $attr }}="{{ $attr_value }}" @endforeach @endif class="btn @if(isset($classes) && filled($classes) && count($classes) !=0) {{ implode(' ', $classes) }}@endif @error($error_name?? $name) is-invalid @enderror" @if(isset($disabled) && $disabled === true) disabled @endif>{!! $label? $label : 'Submit' !!}</button>
@else
    <div class="form-group row">
        @if(isset($label))<label for="{{ $name }}" class="col-sm-4 col-form-label text-md-right">{{ __($label) }}</label>@endif
        <div class="{{ isset($label)? 'col-sm-8' : 'col-sm-12' }}">
            <button type="{{ $type ?? 'button' }}"
            class="btn @if(isset($classes) && filled($classes) && count($classes) !=0) {{ implode(' ', $classes) }}@endif @error($error_name?? $name) is-invalid @enderror" value="{{ $value?? '' }}"
            @if(isset($readonly) && $readonly === true) readonly @endif
            @if(isset($disabled) && $disabled === true) disabled @endif>{{ $btn_label? __($btn_label) : 'Submit' }}</button>
            @if(isset($help_text))
                <small class="form-text text-muted">{{ $help_text }}</small>
            @endif
            {!! $slot !!}
        </div>
    </div>
@endif

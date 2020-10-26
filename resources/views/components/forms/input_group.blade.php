{{-- Stored in /resources/views/compontents/forms/input_group.blade.php --}}
<div class="form-group row{{ (isset($required_if) && filled($required_if))? ' md-required-if-input hidden' : '' }}" @if(isset($required_if) && filled($required_if)) data-required-if="{{ $required_if }}" @endif @if(isset($required_if_value) && filled($required_if_value)) data-required-if-value="{{ $required_if_value }}"@endif>
    @if(isset($label))<label for="{{ $name }}" class="col-sm-4 col-form-label text-md-right">{{ __($label) }}</label>@endif
    <div class="{{ isset($label)? 'col-sm-8' : 'col-sm-12' }}">
        <div class="input-group">
            @if(isset($prepend))
            <div class="input-group-prepend">
                @if(strpos($prepend, '<button') === false)
                    <span class="input-group-text">{!! $prepend !!}</span>
                @else
                    {!! $prepend !!}
                @endif
            </div>
            @endif
            <input id="{{ $id ?? $name }}" @if(isset($attrs) && filled($attrs)) @foreach($attrs as $attr => $attr_value) {{ $attr }}="{{ $attr_value }}" @endforeach @endif name="{{ $name }}" type="{{ $type ?? 'text' }}" @if(isset($inputmask)) data-inputmask="{{ $inputmask }}"  data-inputmask-clearmaskonlostfocus="true" data-inputmask-removemaskonsubmit="true" @endif class="form-control @if(isset($classes) && filled($classes) && count($classes) !=0) {{ implode(' ', $classes) }} @endif @error($error_name?? $name) is-invalid @enderror" placeholder="{{ $placeholder ?? '' }}" value="{{ $value }}" @if(isset($readonly) && $readonly === true) readonly @endif @if(isset($disabled) && $disabled === true) disabled @endif >
            @if(isset($append))
            <div class="input-group-append">
                @if(strpos($append, '<button') === false)
                    <span class="input-group-text">{!! $append !!}</span>
                @else
                    {!! $append !!}
                @endif
            </div>
            @endif
            @if(isset($help_text))
                <small class="form-text text-muted">{{ $help_text }}</small>
            @endif
            {!! $slot !!}
        </div>
    </div>
</div>
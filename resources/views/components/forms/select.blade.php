{{-- Stored in /resources/views/components/select.blade.php --}}
<div class="form-group @if(empty($vertical)) row @endif {{ (isset($required_if) && filled($required_if))? ' md-required-if-input hidden' : '' }}" @if(isset($required_if) && filled($required_if)) data-required-if="{{ $required_if }}" @endif @if(isset($required_if_value) && filled($required_if_value)) data-required-if-value="{{ $required_if_value }}"@endif>
    @if(isset($label))<label for="{{ $label }}" class="@if(empty($vertical)) col-sm-4 col-form-label text-md-right @endif">{{ __($label) }}</label>@endif
    <div class="@if(empty($vertical)) {{ isset($label)? 'col-sm-8' : 'col-sm-12' }} @endif">
        <select id="{{ $id ?? $name }}" @if(isset($attrs) && filled($attrs)) @foreach($attrs as $attr => $attr_value) {{ $attr }}="{{ $attr_value }}" @endforeach @endif name="{{ $name }}" type="{{ $type ?? 'text' }}" class="form-control @if(isset($classes) && filled($classes) && count($classes) !=0) {{ implode(' ', $classes) }} @endif @error($error_name?? $name) is-invalid @enderror"  @if(isset($readonly) && $readonly === true) readonly @endif @if(isset($disabled)) disabled @endif>
            @if(isset($placeholder))
                <option value="" selected disabled>{{ __($placeholder) }}</option>
            @else
                <option value="" selected disabled></option>
            @endif
            @if(isset($options) && filled($options))
                @if(isset($withIndex) && $withIndex === true)
                    @foreach($options as $index => $option)
                        @if(in_array($index, is_array($value) ? $value: [$value] ))
                            <option value="{{ $index }}" selected>{{ ucwords(str_replace('_', ' ', $option)) }}</option>
                        @else
                            <option value="{{ $index }}">{{ ucfirst(str_replace('_', ' ', $option)) }}</option>
                        @endif
                    @endforeach
                @else
                    @foreach($options as $option)
                        @if(in_array($option, is_array($value) ? $value: [$value] ))
                            <option value="{{ $option }}" selected>{{ ucwords(str_replace('_', ' ', $option)) }}</option>
                        @else
                            <option value="{{ $option }}">{{ ucwords(str_replace('_', ' ', $option)) }}</option>
                        @endif
                    @endforeach
                @endif
            @endif
        </select>
        @if(isset($help_text))
            <small class="form-text text-muted">{!! $help_text !!}</small>
        @endif
        {!! $slot !!}
    </div>
</div>
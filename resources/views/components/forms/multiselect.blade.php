{{-- Stored in resources/views/components/multiselect.blade.php --}}
<div class="form-group row{{ (isset($required_if) && filled($required_if))? ' gb-required-if-input hidden' : '' }}" @if(isset($required_if) && filled($required_if)) data-required-if="{{ $required_if }}" @endif @if(isset($required_if_value) && filled($required_if_value)) data-required-if-value="{{ $required_if_value }}"@endif>
    @if(isset($label))<label for="{{ $name }}" class="col-sm-4 col-form-label text-md-right">{{ __($label) }}</label>@endif
    <div class="{{ isset($label)? 'col-sm-8' : 'col-sm-12' }}">
        <div class="md-multi-select">
            <input id="{{ $id ?? $name }}" @if(isset($attrs) && filled($attrs)) @foreach($attrs as $attr => $attr_value) {{ $attr }}="{{ $attr_value }}" @endforeach @endif class="@error($error_name?? $name) is-invalid @enderror" name="{{ (isset($name)) ? $name : '' }}" type="hidden" value="{{ $value?? '' }}">
            <small class="form-text text-muted mb-2">{{ __('Please select at least one option.') }}</small>
            <ul>
                @if(isset($options) && count($options) != 0)
                    @if(isset($withIndex) && $withIndex === true)
                        @foreach($options as $index => $option)
                            <li><span class="btn btn-option btn-secondary" data-value="{{ $index }}" data-selected="false">{{ $option }} <i class="icon hidden fas fa-check"></i></span></li>
                        @endforeach
                    @else
                        @foreach($options as $option)
                            <li><span class="btn btn-option btn-secondary" data-value="{{ $option }}" data-selected="false">{{ $option }} <i class="icon hidden fas fa-check"></i></span></li>
                        @endforeach
                    @endif
                @endif
            </ul>
            {!! $slot !!}
        </div>
    </div>
</div>
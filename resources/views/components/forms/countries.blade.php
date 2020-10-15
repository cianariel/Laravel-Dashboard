{{-- Stored in /resources/views/forms/components/countries.blade.php --}}
<div class="form-group row">
    @if(isset($label))<label for="{{ $name }}" class="col-sm-4 col-form-label text-md-right">{{ __($label) }}</label>@endif
    <div class="{{ isset($label)? 'col-sm-8' : 'col-sm-12' }}">
        <select id="country" name="{{ $name }}" @if(isset($attrs) && filled($attrs)) @foreach($attrs as $attr => $attr_value) {{ $attr }}="{{ $attr_value }}" @endforeach @endif class="form-control @error($error_name?? $name) is-invalid @enderror" @if(isset($readonly) && $readonly === true) readonly @endif @if(isset($disabled)) disabled @endif>
            @foreach(countries() as $country)
                @if($value == $country->code)
                    <option value="{{ $country->code }}" selected>{{ __($country->name) }}</option>
                @elseif(old('country') == '' && $country->code == 'US')
                    <option value="{{ $country->code }}" selected>{{ __($country->name) }}</option>
                @else
                    <option value="{{ $country->code }}">{{ __($country->name) }}</option>
                @endif
            @endforeach
        </select>
        @if(isset($help_text))
            <small class="form-text text-muted">{{ $help_text }}</small>
        @endif
        {!! $slot !!}
    </div>
</div>
{{-- Stored in /resources/views/forms/components/states.blade.php --}}
<div class="form-group row">
    @if(isset($label))<label for="{{ $label }}" class="col-sm-4 col-form-label text-md-right">{{ __($label) }}</label>@endif
    <div class="{{ isset($label)? 'col-sm-8' : 'col-sm-12' }}">
       <input id="other-state" type="text" class="hidden form-control @error($error_name?? $name) is-invalid @enderror" name="" value="{{ $value }}" placeholder="{{ __('State/Province') }}">
       <select id="usa-states" @if(isset($attrs) && filled($attrs)) @foreach($attrs as $attr => $attr_value) {{ $attr }}="{{ $attr_value }}" @endforeach @endif name="{{ $name }}" class="form-control @error($error_name?? $name) is-invalid @enderror" @if(isset($readonly) && $readonly === true) readonly @endif @if(isset($disabled)) disabled @endif>
            @foreach(states('US') as $state)
                @if($state->status == App\Models\System\State::ACTIVE)
                    @if($state->code == $value)
                        <option value="{{ $state->code }}" selected>{{ __($state->name) }}</option>
                    @else
                        <option value="{{ $state->code }}">{{ __($state->name) }}</option>
                    @endif
                @endif
            @endforeach
        </select>
        @if(isset($help_text))
            <small class="form-text text-muted">{{ $help_text }}</small>
        @endif
        {!! $slot !!}
    </div>
</div>
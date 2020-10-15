{{-- Stored in /resources/views/components/vendor/stripe/element.blade.php --}}
@isset($intent)
    <div class="form-group row mb-3">
        <label for="card-holder-name" class="col-sm-12 col-form-label">{{ __('Name on card') }}</label>
        <div class="col-sm-12">
            @component('components.forms.input', ['id' => 'card_holder_name', 'type', 'text' , 'name' => 'card_holder_name', 'value' => old('card_holder_name')])
                @error('card-holder-name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            @endcomponent
        </div>
    </div>
@endif
<div class="form-group row{{ (isset($required_if) && filled($required_if))? ' gb-required-if-input hidden' : '' }}" @if(isset($required_if) && filled($required_if)) data-required-if="{{ $required_if }}" @endif @if(isset($required_if_value) && filled($required_if_value)) data-required-if-value="{{ $required_if_value }}"@endif>
    @if(isset($label))<label for="{{ $id ?? $name }}" class="col-sm-4 col-form-label text-md-right">{{ __($label) }}</label>@endif
    <div class="{{ isset($label)? 'col-sm-8' : 'col-sm-12' }}">
        <div id="{{ $id ?? $name ?? 'stripe-element' }}" class="bg-light-grey-alt p-3"></div>
        <div id="card-errors" class="mt-2 mb-2 fg-primary"></div>
        @if(isset($help_text))
            <small class="form-text text-muted">{!! $help_text !!}</small>
        @endif
        @isset($intent)
            <div class="mt-3">
                <small class="d-block mb-2 fg-grey gb-verify-card-label">{{ __($verify_message?? 'Please click verify card for security before you click Pay') }}</small>
                <button id="card-button" type="button" class="btn btn-primary" data-secret="{{ $intent->client_secret }}">{{ __('Verify Card') }}</button>
            </div>
        @endisset
        {!! $slot !!}
    </div>
</div>
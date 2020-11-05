{{-- Stored in /resources/view/frontend/setup/subscription/partials/form/billing.blade.php --}}
<div class="card card-payment-form">
    <div class="card-header bg-white border-0 font-weight-bold fg-blue">
        {{ __('Billing Information') }}
    </div>
    <div class="card-body">
        @component('components.forms.input', [
            'id'        => 'first_name',
            'type'      => 'text',
            'label'     => __('First Name'),
            'name'      => 'first_name',
            'value'     => old('first_name')?? $user->first_name
        ])
            @error('first_name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        @endcomponent
        @component('components.forms.input', [
            'id'        => 'last_name',
            'type'      => 'text',
            'label'     => __('Last Name'),
            'name'      => 'last_name',
            'value'     => old('last_name')?? $user->last_name
        ])
            @error('last_name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        @endcomponent
        @component('components.forms.input', [
            'id'        => 'address',
            'type'      => 'text',
            'label'     => __('Address'),
            'name'      => 'address',
            'value'     => old('address')?? $user->address
        ])
            @error('address')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        @endcomponent
        @component('components.forms.input', [
            'id'        => 'address_2',
            'type'      => 'text',
            'label'     => __('Apt/Unit'),
            'name'      => 'address_2',
            'value'     => old('address_2')?? $user->address_2
        ])
            @error('address_2')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        @endcomponent
        @component('components.forms.input', [
            'type'          => 'text',
            'name'          => 'city',
            'label'         => __('City/Town'),
            'value'         => old('city')?? $user->city,
            'placeholder'   => 'City/Town'
        ])
            @error('city')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        @endcomponent
        @component('components.forms.input', [
            'type'          => 'text',
            'name'          => 'zipcode',
            'label'         => __('Zipcode'),
            'value'         => old('zipcode')?? $user->zipcode,
            'placeholder'   => '91020'
        ])
            @error('zipcode')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        @endcomponent
        @component('components.forms.states', [
            'label'     => __('State/Province'),
            'name'      => 'state',
            'value'     => old('state')?? $user->state
        ])
            @error('state')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        @endcomponent
        @component('components.forms.select', [
            'id'        => 'country',
            'name'      => 'country',
            'label'     => __('Country'),
            'options'   => $countries,
            'value'     => old('country')?? $user->country,
            'withIndex' => true
        ])
            @error('country')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        @endcomponent

    </div>
</div>
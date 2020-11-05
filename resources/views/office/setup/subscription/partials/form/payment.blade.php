{{-- Stored in /resources/views/office/setup/office/subscription/partials/form/payment.blade.php --}}
<div class="card card-payment-info rounded bg-white border-top">
    <div class="card-body">
        <h5 class="card-title bg-white border-0 font-weight-bold fg-blue font-sm-size">
            {{ __('Payment Information') }}
        </h5>
        <div class="card-details hidden">
            @component('components.vendor.stripe.element', [
                'id'        => 'stripe-element',
                'name'      => 'stripe-element',
                'classes'   => ['pb-3'], 'intent' => $intent
            ])@endcomponent
            <div class="form-group row mb-0 justify-content-center">
                <div class="col-12 text-right">
                    @component('components.forms.button', [
                        'id'        => 'btn-pay',
                        'name'      => 'submit',
                        'type'      => 'submit',
                        'disabled'  => true ,
                        'classes'   => ['btn-primary'], 'label' => 'Select a Package'
                    ])
                        <a class="btn btn-secondary" href="{{ route('office.dashboard') }}">{{ __('Cancel') }}</a>
                    @endcomponent
                </div>
            </div>
        </div>
        <div class="package-not-selected text-center mt-4 pt-3">
            <p class="card-text font-xxl-size p-0 m-0"><i class="fas fa-exclamation-circle"></i></p>
            <p class="card-text font-weight-bold font-lg-size">{{ __('Please select a package.') }}</p>
        </div>
    </div>
</div>
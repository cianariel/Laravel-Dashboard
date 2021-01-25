{{-- Stored in resources/views/partners/setup/subscription/index.blade.php --}}
@extends('frontend.layouts.master')
@section('html-title', __('Select a Package'))
@section('page-class', 'setup-package')
@section('canonical-url', url()->full())
@section('content-body')
<section class="section position-relative d-flex justify-content-center align-items-center">
    <div class="container-fluid m-0 p-0">
        <div class="row justify-content-center">
            <div class="col-12">
                @component('components.bootstrap.card', ['classes' => ['m-0', 'p-0']])
                    <div class="card-body p-0 m-0">
                        @component('components.forms.form', [
                            'id'            => 'payment-form',
                            'method'        => 'POST',
                            'action'        => route('user.setup.account.subscription.store'),
                            'confirmed'     => true,
                            'dialog_title'  => __('Are you sure you want to continue?'),
                            'classes'       => ['no-form-update-handler']
                        ])
                            @component('components.forms.hidden', [
                                'id' => 'stripeToken',
                                'name' => 'stripeToken',
                                'value' => ''])
                            @endcomponent
                            @component('components.forms.hidden', [
                                'id' => 'payment_method',
                                'name' => 'payment_method',
                                'value' => ''
                            ])@endcomponent
                            <section id="setup-package-section" class="section pt-5 pb-5 bg-light-grey">
                                @component('components.bootstrap.container',[
                                    'fluid' => true
                                ])
                                    <div class="row justify-content-center">
                                        <div class="col-12">
                                            <div class="card-deck-packages bg-white">
                                                {{--[packages]--}}
                                                @include('user.setup.subscription.partials.form.packages')
                                                {{--[/packages]--}}
                                            </div>
                                        </div>
                                    </div>
                                    @if(isset($packages) && $packages->count() > 1)
                                    <div class="row justify-content-center">
                                        <div class="col-12">
                                            <div class="card-deck card-deck-payment-info mt-3">
                                                @include('user.setup.subscription.partials.form.billing')
                                                @include('user.setup.subscription.partials.form.payment')
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @endcomponent
                            </section>
                        @endcomponent
                    </div>
                @endcomponent
            </div>
        </div>
    </div>
</section>
@endsection
@section('scripts_end')
<script type="text/javascript">
<!--
    jQuery(document).ready(function($){

        let form = $('#payment-form');
        let btn = form.find('#btn-pay');
        let packages = $('.card-deck-packages .card-package');
        let card_payment_info = form.find('.card-payment-info');
        let package_not_selected = form.find('.package-not-selected');
        let verify_label = $('.md-verify-card-label');
        let verifedPayment = false;
        let selectedCount = 0;

        var style = {
          base: {
            color: '#32325d',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
              color: '#aab7c4'
            }
          },
          invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
          }
        };

        let stripe = MD.stripe();
        let stripe_element = $('#stripe-element');
        let elements = stripe.elements();
        let card = elements.create('card', {style: style});

        // Mount
        card.mount('#' + stripe_element.attr('id'));

        card.addEventListener('change', function(event) {
          var displayError = document.getElementById('card-errors');
          if (event.error) {
            displayError.textContent = event.error.message;
          } else {
            displayError.textContent = '';
          }
        });

        let packageSelectedHandler = function() {

            let total = 0;

            packages.each(function(e){
                let package = $(this);
                let toggler = package.find('.md-toggler');
                let input = toggler.find('input[id^="package"]');
                let selected = toggler.data('selected');
                let price = package.data('price');

                if(typeof selected == 'string') {
                    selected = (selected == 'true')? true : false;
                }

                if(selected === true) {
                    package_not_selected.addClass('hidden');
                    card_payment_info.find('.card-details').removeClass('hidden');
                    package.data('selected', true);
                    total += parseInt(price);

                    if(verifedPayment) {
                        btn.prop('disabled', false);
                    }
                }
            }).promise().done(function(){

                if(total !== 0) {
                    total /= 100;
                    btn.html('<i class="fas fa-lock"></i> {{ __('Pay') }} $' + (total.toFixed(2)));

                    if(verifedPayment) {
                        btn.prop('disabled', false);
                    }
                } else {
                    btn.html('{{ __('Select a Package') }}');
                    if(verifedPayment) {
                        btn.prop('disabled', false);
                    }
                }
            });

        };

        // Package select handling.
        packageSelectedHandler();

        // Form change handler
        form.change(function(e){
            packageSelectedHandler();
        });

        // Submit the form with the token ID.
        let stripeTokenHandler = function(token) {
          // Insert the token ID into the form so it gets submitted to the server
          form.find('#stripeToken').val(token.id)
        }

        form[0].addEventListener('submit', function(event) {
            event.preventDefault();
            form.submit();
        });

        @isset($intent)
            const cardHolderName = document.getElementById('card_holder_name');
            const cardButton = document.getElementById('card-button');
            const clientSecret = cardButton.dataset.secret;

            let paymentIntentTokenHandler = function(paymentIntent) {
                form.find('#payment_method').val(paymentIntent);
                form.find('#card-button').addClass('hidden').prop('disabled', true);
            }

            cardButton.addEventListener('click', async (e) => {
                const { setupIntent, error } = await stripe.confirmCardSetup(
                    clientSecret, {
                        payment_method: {
                            card: card,
                            billing_details: { name: cardHolderName.value }
                        }
                    }
                );

                if (error) {
                    MD.dialog(@json(__('Card Verification Failed')), error.message);
                    btn.prop('disabled', true);
                } else {
                    paymentIntentTokenHandler(setupIntent.payment_method);

                    stripe.createToken(card).then(function(result) {
                        if (result.error) {
                            // Inform the user if there was an error.
                            var errorElement = document.getElementById('card-errors');
                            errorElement.textContent = result.error.message;
                        } else {
                            // Send the token to your server.
                            stripeTokenHandler(result.token);
                        }
                    });

                    packageSelectedHandler();
                    btn.prop('disabled', false);
                    verifedPayment = true;
                    verify_label.addClass('hidden');
                    MD.dialog(@json(__('Card Verified')), @json(__('Thank you for verifying your card.')));
                }
            });
        @endisset

    });
//-->
</script>
@endsection
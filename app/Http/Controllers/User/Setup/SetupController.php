<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Setup;

use App\Http\Controllers\User\BaseController;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;
use App\Models\System\Country;
use App\Models\System\State;
use App\Models\System\Role;
use App\Models\System\User;
use App\Models\System\Subscription;
use App\Models\System\Package;
use App\Models\System\Product;
use App\Models\System\Payment;
use App\Rules\CreditCardRule;
use App\Rules\PhoneRule;
use App\Rules\SanitizeHtml;
use Stripe\Stripe as Stripe;
use Stripe\Exception\InvalidRequestException;
use Stripe\Exception\CardException;
use Laravel\Cashier\Exceptions\SubscriptionUpdateFailure;
use App\Events\Office\Subscription\EventSubscriptionCreated;
use Exception;

/**
 * SetupController
 *
 * @author    Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MdRepTime, LLC
 * @package   App\Http\Controllers\User\Setup
 */
class SetupController extends BaseController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->middleware('force.https');
        $this->middleware('auth');
        $this->middleware('role:' . Role::USER);
        $this->middleware('user:' . User::GUARD);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $site = site(config('app.base_domain'));
        $user = auth()->guard(User::GUARD)->user();

        if ($user->setup_completed != User::SETUP_COMPLETED) {
            $countries = countries(false);
            $_countries = [];

            foreach ($countries as $country) {
                if ($countries->status = Country::ACTIVE) {
                    $_countries[$country->code] = $country->name;
                }
            }

            $countries = $_countries;

            $breadcrumbs = breadcrumbs([
                __('Dashboard') => [
                    'path'      => route('user.dashboard'),
                    'active'    => false
                ],
                __('Setup')     => [
                    'path'      => route('user.setup.account'),
                    'active'    => true
                ]
            ]);

            return view('user.setup.profile',
                compact('site', 'user', 'countries', 'breadcrumbs')
            );
        }

        flash(__('Unauthorized access.'));
        return redirect('/');
    }

    /**
     * Select subscription package
     *
     * @param  \Iluminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function selectSubscription(Request $request)
    {
        $site = site(config('app.base_domain'));
        $user = auth()->guard(User::GUARD)->user();

        if (!$user->subscribed('default')) {
            $countries = countries(false);
            $_countries = [];

            foreach ($countries as $country) {
                if ($countries->status = Country::ACTIVE) {
                    $_countries[$country->code] = $country->name;
                }
            }

            $countries = $_countries;

            $packages = $site->packages()->where('status', Package::ACTIVE)->cursor();
            $intent = $user->createSetupIntent();

            $breadcrumbs = breadcrumbs([
                __('Dashboard') => [
                    'path'      => route('user.dashboard'),
                    'active'    => false
                ],
                __('Setup')     => [
                    'path'      => route('user.setup.account'),
                    'active'    => false
                ],
                __('Subscription') => [
                    'path'      => route('user.setup.account.subscription.signup'),
                    'active'    => true
                ]
            ]);

            return view('user.setup.subscription.index',
                compact('site', 'user', 'breadcrumbs', 'countries', 'packages', 'intent')
            );
        }

        flash(__('Unauthorized access.'));
        return redirect('/');
    }

    /**
     * Save user profile
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function saveUserProfile(Request $request)
    {
        $rules = [
            'profile_image'     => ['file', 'nullable','image', 'mimes:jpeg,gif,png', 'max:' . bit_convert(10, 'mb')],
            'company'           => ['nullable', 'string', 'max:50', new SanitizeHtml],
            'first_name'        => ['required', 'string', 'max:50', new SanitizeHtml],
            'last_name'         => ['required', 'string', 'max:50', new SanitizeHtml],
            'address'           => ['required', 'string', 'max:100', new SanitizeHtml],
            'address_2'         => ['nullable', 'string', 'max:100', new SanitizeHtml],
            'city'              => ['required', 'string', 'max:50', new SanitizeHtml],
            'state'             => ['required', 'string', 'max:50'],
            'zipcode'           => ['required', 'string', 'max:25'],
            'country'           => ['required', 'string', 'max:2', 'exists:system.countries,code'],
            'phone'             => ['nullable', 'string', new PhoneRule],
            'mobile_phone'      => ['required', 'string', new PhoneRule]
        ];

        $validatedData = $request->validate($rules);

        $site = site(config('app.base_domain'));
        $user = auth()->guard(User::GUARD)->user();

        $user->company = $request->input('company');
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->address = $request->input('address');
        $user->address_2 = $request->input('address_2');
        $user->city = $request->input('city');
        $user->state = $request->input('state');
        $user->zipcode = $request->input('zipcode');
        $user->country = $request->input('country');
        $user->phone = $request->input('phone');
        $user->mobile_phone = $request->input('mobile_phone');
        $user->save();

        if ($request->hasFile('profile_image')) {
            $image = $user->getMedia('profile_image')->first();

            if ($image) {
                $image->delete();
            }

            $file = $request->file('profile_image');

            $user->addMedia($file)
                ->toMediaCollection('profile_image');
        }

        return redirect()->route('user.setup.account.subscription.signup');
    }

    /**
     * Save selected subscription
     *
     * @param  \Iluminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function createSubscription(Request $request)
    {
        $site = site(config('app.base_domain'));
        $user = auth()->guard(User::GUARD)->user();

        if ($user->subscribed('default') === true) {
            return redirect()->route('user.dashboard');
        }

        $rules = [
            'packages.*'        => ['nullable', 'string', 'exists:system.packages,name', new SanitizeHtml()],
            'first_name'        => ['required', 'string', 'max:50', new SanitizeHtml()],
            'last_name'         => ['required', 'string', 'max:50', new SanitizeHtml()],
            'address'           => ['required', 'string', 'max:100', new SanitizeHtml()],
            'address_2'         => ['nullable', 'string', 'max:100', new SanitizeHtml()],
            'city'              => ['required', 'string', 'max:50', new SanitizeHtml()],
            'state'             => ['required', 'string', 'max:50', new SanitizeHtml()],
            'zipcode'           => ['required', 'string', 'max:25', new SanitizeHtml()],
            'country'           => ['required', 'string', 'exists:countries,code'],
            'card_holder_name'  => ['required', 'string', 'max:255', new SanitizeHtml()],
            'stripeToken'       => ['required', 'string', 'max:255', new SanitizeHtml()],
            'payment_method'    => ['required', 'string', 'max:255', new SanitizeHtml()]
        ];

        $validatedData = $request->validate($rules);

        $packagesSelectedCount = 0;

        // Allow only one package to be selected.
        if ($packagesSelected = $request->input('packages')) {
            if (count($packagesSelected)) {
                foreach ($packagesSelected as $packageSelected) {
                    if (filled($packageSelected)) {
                        if ($packagesSelectedCount <= 0) {
                            $packagesSelectedCount = 1;
                        } else {
                            flash(__('Can not select more one then one subscription plan'));
                            return redirect()->route('user.setup.account.subscription.signup')->withInput();
                        }
                    }
                }
            }
        }

        // Update User
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->address = $request->input('address');
        $user->address_2 = $request->input('address_2');
        $user->city = $request->input('city');
        $user->state = $request->input('state');
        $user->zipcode = $request->input('zipcode');
        $user->country = $request->input('country');

        $packages = $site->packages()
            ->where('status', Package::ACTIVE)
            ->whereIn('name', $packagesSelected)
            ->cursor();

        $cardStripeToken = $request->input('stripeToken');
        $stripePaymentMethod = $request->input('payment_method');

        // Create a new stripe customer or get previous
        $stripe_error = '';

        try {
            $stripeCustomer = $user->createOrGetStripeCustomer([
                'name'              => (filled($user->company)) ? $user->first_name . ' ' . $user->last_name . '(' . $user->company . ')' : $user->first_name . ' ' . $user->last_name,
                'description'       => $user->username,
                'address'           => [
                    'line1'         => $user->address,
                    'line2'         => $user->address_2 ?? '',
                    'city'          => $user->city,
                    'state'         => $user->state,
                    'postal_code'   => $user->zipcode,
                    'country'       => $user->country,
                ]
            ]);
        } catch (Exception $e) {
            logger("[App\Http\Controllers\User\Setup\SetupController] - {$e->getMessage()}");
            flash($e->getMessage());
            return redirect()->route('user.setup.account.subscription.signup')->withInput();
        }

        // Check if any stripe errors
        if (filled($stripe_error)) {
            flash($stripe_error);
            return redirect()->route('user.setup.account.subscription.signup')->withInput();
        }

        $stripeDefaultPaymentMethod = Payment::getStripePaymentMethod($stripePaymentMethod, $stripe_error);

        // Check if any stripe errors
        if (filled($stripe_error)) {
            flash($stripe_error);
            return redirect()->route('user.setup.account.subscription.signup')->withInput();
        }

        // Save card information
        $user->card_full_name = $request->input('card_holder_name');
        $user->card_brand = $stripeDefaultPaymentMethod->card->brand;
        $user->card_funding = $stripeDefaultPaymentMethod->card->funding;
        $user->card_country = $stripeDefaultPaymentMethod->card->country;
        $user->card_exp_month = safe_integer($stripeDefaultPaymentMethod->card->exp_month);
        $user->card_exp_year = safe_integer($stripeDefaultPaymentMethod->card->exp_year);
        $user->card_last_four = safe_integer($stripeDefaultPaymentMethod->card->last4);

        $user->addPaymentMethod($stripeDefaultPaymentMethod->id);
        $user->updateDefaultPaymentMethod($stripeDefaultPaymentMethod->id);

        // Selected plans ids
        $stripePlans = [];
        $stripePlanTotal = 0;

        foreach ($packages as $plan) {
            $stripePlans[] = $plan->stripe_plan;
            $stripePlanTotal += $plan->price;
        }

        // Validate
        if (filled($stripePlans)) {
            $subscriptedCreated = false;

            try {
                if (count($stripePlans) == 1) {
                    // Create new Scription
                    $user->newSubscription('default', $stripePlans[0])
                        ->create($stripePaymentMethod);
                } else {
                    $user->newSubscription('default', $stripePlans)
                        ->create($stripePaymentMethod);
                }

                $subscriptedCreated = true;
            } catch (SubscriptionUpdateFailure $e) {
                logger("[App\Http\Controllers\User\Setup\SetupController] - {$e->getMessage()}");
                flash(__('Error occured, please try again later.'));
                return redirect()->route('user.setup.account.subscription.signup')->withInput();
            } catch (Exception $e) {
                logger("[App\Http\Controllers\User\Setup\SetupController] - {$e->getMessage()}");
                flash(__('Error occured, please try again later.'));
                return redirect()->route('user.setup.account.subscription.signup')->withInput();
            }

            if ($subscriptedCreated === false) {
                flash(__('Please check your billing and payment information.'));
                return redirect()->route('user.setup.account.subscription.signup')->withInput();
            }

            try {
                 $user->saveOrFail();
            } catch (QueryException $e) {
                logger("[App\Http\Controllers\User\Setup\SetupController] - {$e->getMessage()}");
                flash(__('System error occured saving your profile. Please contact support.'));
                return redirect()->route('user.setup.account.subscription.signup')->withInput();
            } catch (Exception $e) {
                logger("[App\Http\Controllers\User\Setup\SetupController] - {$e->getMessage()}");
                flash(__('System error occured saving your profile. Please contact support.'));
                return redirect()->route('user.setup.account.subscription.signup')->withInput();
            }

            $subscriptions = Subscription::where('user_id', $user->id)->cursor();

            foreach ($subscriptions as $subscription) {
                // Save subscription reference for faster access.
                $site->assignSubscription($subscription); // Save a copy for faster access
                event(new EventSubscriptionCreated($user, $subscription));
            }

            return redirect()->route('user.setup.complete');
        }

        flash(__('Unauthorized access.'));
        return redirect('/');
    }

    /**
     * Setup Complete Page
     *
     * @param  \Iluminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function thankyou(Request $request)
    {

        $site = site(config('app.base_domain'));
        $user = auth()->guard(User::GUARD)->user();

        if ($user->subscribed('default') == true) {
            $breadcrumbs = breadcrumbs([
                __('Dashboard') => [
                    'path'      => route('user.dashboard'),
                    'active'    => false
                ],
                __('Setup')     => [
                    'path'      => route('user.setup.account'),
                    'active'    => false
                ],
                __('Subscription') => [
                    'path'      => route('user.setup.account.subscription.signup'),
                    'active'    => false,
                ],
                __('Complete')  => [
                    'path'      => route('user.setup.complete'),
                    'active'    => true
                ]
            ]);

            return view('user.setup.subscription.thankyou', compact('site', 'user', 'breadcrumbs'));
        }

        flash(__('Unauthorized access.'));
        return redirect('/');
    }
}

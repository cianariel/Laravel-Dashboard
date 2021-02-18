<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Offices;

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
 * OfficesController
 *
 * @author    Taylor <sykestaylor122@gmail.com>
 * @copyright 2020 MdRepTime, LLC
 * @package   App\Http\Controllers\User\Offices
 */
class OfficesController extends BaseController
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

    private function checkCompletedProfile()
    {
        $user = auth()->guard(User::GUARD)->user();

        if ($user->setup_completed != User::SETUP_COMPLETED) {
            flash(__('Unauthorized access.'));
            return redirect('/');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->checkCompletedProfile();

        $breadcrumbs = breadcrumbs([
            __('Dashboard') => [
                'path'      => route('user.dashboard'),
                'active'    => false
            ],
            __('Offices')     => [
                'path'      => route('user.offices.index'),
                'active'    => true
            ]
        ]);

        return view('user.offices.index',
            compact('breadcrumbs')
        );

    }

    /**
     * Add an office page
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
        $this->checkCompletedProfile();

        $breadcrumbs = breadcrumbs([
            __('Dashboard') => [
                'path'      => route('user.dashboard'),
                'active'    => false
            ],
            __('Offices')     => [
                'path'      => route('user.offices.index'),
                'active'    => false
            ],
            __('Add')     => [
                'path'      => route('user.offices.add'),
                'active'    => true
            ]
        ]);

        return view('user.offices.add',
            compact('breadcrumbs')
        );
    }

    
}

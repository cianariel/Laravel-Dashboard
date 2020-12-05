<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\System\User;
use App\Models\System\Role;
use App\Models\System\Subscription;
use Auth;
use Closure;
use Carbon\Carbon;

class CheckSubscriptionTrial
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Check if subscription is in trial
        if (auth()->guard(User::GUARD)->check()) {
            $user = $request->user();

            if ($user->hasRole(Role::USER) && $user->subscribed('default')) {
                if ($user->subscription('default')->onTrial()) {
                    $columns = [
                        'id',
                        'trial_ends_at'
                    ];

                    $subscription = $user->subscription('default');

                    flash('Your 15 day trial expires in ' . Carbon::parse($subscription->trial_ends_at)->diffForHumans() . '. <a href="#" class="font-weight-bold">' . __('Click here to activate full subscription.') . '</a>')->warning();
                }
            }
        }

        return $next($request);
    }
}

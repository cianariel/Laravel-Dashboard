<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\System\User;
use App\Models\System\Role;
use Illuminate\Http\Request;
use Closure;

class UserSubscribed
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
        if (auth()->check()) {
            $user = auth()->guard(User::GUARD)->user();

            if ($user->hasRole(Role::USER)) {
                // Check if user hasn't paid subscription.
                if ($user->setup_completed == User::SETUP_COMPLETED && $user->subscribed('default') !== true) {
                    // redirect to billing
                }

                // Check if hasn't choose a subscription
                if ($user->setup_completed == User::SETUP_INCOMPLETE) {
                    return redirect()->route('user.setup.account');
                }
            }
        }

        return $next($request);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\System\User;
use Closure;

class UserSubscribed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->guard(User::GUARD)->user()) {
            $user = $request->user();

            // Check if user hasn't paid subscription.
            if ($user->setup_completed == User::SETUP_COMPLETED && $user->subscribed('default') !== true) {
                // redirect to billing
            }

            // Check if hasn't choose a subscription
            if ($usre->setup_completed == User::SETUP_INCOMPLETE) {
                return redirect('setup');
            }

            // Redirect if already to subscribed.
            if ($user->setup_completed == User::SETUP_COMPLETED && $user->subscribed('default')) {
                return redirect()->to(portal_url($user));
            }
        }

        return $next($request);
    }
}

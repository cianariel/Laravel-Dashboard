<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Auth;
use Cache;
use Carbon\Carbon;

/**
 * Tracks user activity middleware.
 *
 * @author    Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MDRepTime, LLC
 * @package   App\Http\Middleware
 */
class LastUserActivity
{
    /**
     * Minutes last user activity cache expires
     *
     * @var    string $expiresMinutes
     * @access protected
     */
    protected $expiresMinutes = 5;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     * @access public
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (auth()->guard($guard)->check()) {
            $expiresAt = Carbon::now()->addMinutes($this->expiresMinutes);
            Cache::put('user-is-online-' . auth()->user()->id, true, $expiresAt);
        }

        return $next($request);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;

/**
 * AjaxRequest Middleware
 *
 * @author Antonio Vargas <localhost.80@gmail.com>
 * @copyright 2020 MdRepTime, LLC
 * @package App\Http\Middleware
 */
class AjaxRequest
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
        if(!$request->ajax()) {
            abort(404);
        }

        return $next($request);
    }
}

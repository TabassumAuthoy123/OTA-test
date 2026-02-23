<?php

namespace App\Http\Middleware;

use Closure;
use App\Enums\UserType;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->user_type == UserType::Admin->value) {
            return $next($request);
        } else {
            return abort(401);
        }

    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Cookie;

class ForceSameSiteNone
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // সব কুকির জন্য SameSite=None enforce করা
        foreach ($response->headers->getCookies() as $cookie) {
            $cookie->setSameSite('None');
            $cookie->setSecure(true); // SameSite=None হলে Secure অবশ্যই true হতে হবে
        }

        return $response;
    }
}

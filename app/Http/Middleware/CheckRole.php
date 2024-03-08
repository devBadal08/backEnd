<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        $user_roles = $user->getRoleNames()->toArray();
        dd($user_roles);

        if ($user && count(array_intersect($roles, $user_roles)) > 0) {

            return $next($request);
        }
      
        return redirect()->back()->withError('Unauthorized');
    }
}

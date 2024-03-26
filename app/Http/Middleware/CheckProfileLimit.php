<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class CheckProfileLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = $request->user()->id;

        if ($userId) {
            $user = User::find($userId);
            if ($user) {
                $profilesCount = $user->profiles()->count();
                if ($profilesCount >= $user->max_profiles_limit) {
                    return response()->json(['error' => 'Maximum limit reached for adding profiles under this manager.'], 403);
                }
            }
        }

        return $next($request);
    }
}

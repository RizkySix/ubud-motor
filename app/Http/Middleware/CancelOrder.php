<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CancelOrder
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($request->user() instanceof User && $request->user()->email_verified_at === null){
            return response()->json([
                'status' => false,
                'data' => 'Please verify your email first'
            ], 403);
        }
        return $next($request);
    }
}

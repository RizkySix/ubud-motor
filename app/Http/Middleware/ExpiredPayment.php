<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExpiredPayment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $currentRoute = $request->route()->getName();

        switch ($currentRoute) {
            case 'update.booking':
                $model = $request->route('booking');
                break;
            case 'update.rental.extension':
                $model = $request->route('rentalExtension');
                break;
        }

        if(Carbon::parse($model->expired_payment) < now()){
            return response()->json([
                'status' => false,
                'data' => 'Cant update order was expired'
            ] , 403);
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;

class HasKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->hasHeader('x-api-key')) {
            return response([
                'message' => 'invalid api key',
            ], 403);
        }

        $key = $request->header('x-api-key');
        $setting = Setting::first();

        if ($setting->key != $key) {
            return response([
                'message' => 'invalid api key',
            ], 403);
        }

        return $next($request);
    }
}

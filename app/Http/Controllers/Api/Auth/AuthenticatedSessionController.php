<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // check is rate limit return too many request response
        if ($this->isRateLimit($request)) {
            // lock the request
            event(new Lockout($request));

            return response([
                'message' => 'too many request',
            ], 429);
        }

        // get user
        $user = User::where('email', $request->input('email'))->first();

        // check if not user and password not match
        if (!$user || !Hash::check($request->input('password'), $user->getAuthPassword())) {
            // hit the rate limit incress by one
            RateLimiter::hit($this->throttleKey($request));
            return response([
                'message' => 'Invalid Credentials',
                'error' => 'email or password is in correct',
            ], 422);
        }

        // create token
        $token = $user->createToken($user->name)->plainTextToken;

        // clear rate limit
        RateLimiter::clear($this->throttleKey($request));

        // return success response
        return response([
            'message' => 'user login success',
            'data' => [
                'token' => $token,
            ],
        ], 200);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request): Response
    {
        $request->user()->currentAccessToken()->delete();

        return response(['message' => 'user logout success'], 200);
    }

    /**
     * Check is rate limit
     *
     * @param Request $request
     * @return bool
     */
    private function isRateLimit(Request $request): bool
    {
        return RateLimiter::tooManyAttempts($this->throttleKey($request), 5);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @param Request $request
     * @return string
     */
    private function throttleKey(Request $request): string
    {
        return str()->lower($request->input('email')) . '|' . $request->ip();
    }
}

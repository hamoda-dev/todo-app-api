<?php

namespace App\Http\Controllers\Api\Auth;

use App\Actions\CreateUser;
use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  CreateUser $createUser
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, CreateUser $createUser)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $feedback = (object) $createUser([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => Role::User,
        ]);

        event(new Registered($feedback->user));

        $token = $feedback->user->createToken($feedback->user->name)->plainTextToken;

        return response([
            'message' => 'user register success',
            'data' => [
                'user' => (new UserResource($feedback->user)),
                'token' => $token,
            ],
        ], 201);
    }
}

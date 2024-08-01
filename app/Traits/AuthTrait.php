<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait AuthTrait
{
    public function attemptLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');
        return Auth::attempt($credentials);
    }
}
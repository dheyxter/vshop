<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Traits\AuthTrait;
use Inertia\Inertia;
use Inertia\Response;

use App\Http\Controllers\Admin\AdminAuthController;

use App\Models\User;


class AuthenticatedSessionController extends Controller
{
    use AuthTrait;
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        if ($this->attemptLogin($request)) {
            $user = Auth::user();
            if ($user->isAdmin == 1) {
                return redirect()->action([AdminAuthController::class, 'login'], ['request' => $request]);
            } else {
                $request->authenticate();

                $request->session()->regenerate();

                return redirect()->intended(RouteServiceProvider::HOME);
            }
        }
         
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

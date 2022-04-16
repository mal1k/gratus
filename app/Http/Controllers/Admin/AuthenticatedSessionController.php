<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    Auth,
    Cookie
};

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        /**
         * We should destroy the 'atoken' here also
         * because the session may expired before expiration of the token
         */
        return response()->view('admin.login')->cookie(Cookie::forget('atoken'));
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $user = User::where('email', '=', $request->email)->first();

        if (!empty($user->verificate_code))
            return back()->withErrors(['error' => 'Verificate your email.']);

        $request->authenticate();

        $request->session()->regenerate();

        $request->user()->tokens()->where('name', 'fully-fledged-token')->delete();

        $token = $request->user()->createToken('fully-fledged-token')->plainTextToken;

        $cookie = cookie('atoken', $token, 60 * 24 * 120, '', '', false, false);

        return redirect()->intended(RouteServiceProvider::HOME)->cookie($cookie);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/manager')->cookie(Cookie::forget('atoken'));
    }
}

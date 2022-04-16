<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Organiztions\LoginRequest;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    Auth,
    Cookie
};
use App\Providers\RouteServiceProvider;

class AuthOrganizationController extends Controller
{
    public function loginPage($org)
    {
        $orgInfo = Organization::where('slug', '=', $org)->first();

        if ( empty($orgInfo) )
          return abort(404);

        return view('admin.authOrganization.login');
    }

    public function login($org, LoginRequest $request)
    {
        $orgInfo = Organization::where('slug', '=', $org)->first();

        if ( empty($orgInfo) )
          return abort(404);
        elseif ( $orgInfo->email != $request->email )
            return back()->withErrors(['error' => 'These credentials do not match our records.']);

        $request->authenticate();

        $request->session()->regenerate();

        Auth::guard('organization')->user()->tokens()->where('name', 'fully-fledged-token')->delete();

        $token = Auth::guard('organization')->user()->createToken('fully-fledged-token')->plainTextToken;

        $cookie = cookie('atoken', $token, 60 * 24 * 120, '', '', false, false);

        return redirect('/organization/'.$orgInfo->slug.'/dashboard')->cookie($cookie);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($name, Request $request)
    {
        Auth::guard('organization')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect("organization/$name/login")->cookie(Cookie::forget('atoken'));
    }
}

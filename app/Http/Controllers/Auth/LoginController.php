<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        // $this->middleware('auth')->only('logout');
    }

    public function login(Request $request): RedirectResponse
    {
        $input = $request->all();

        $this->validate($request, [
            'login' => 'required',
            'password' => 'required',
        ]);

        $loginType = filter_var($input['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$loginType => $input['login'], 'password' => $input['password']])) {
            if (Auth::user()->role === 'administrator') {
                return redirect()->route('administrator.dashboard');
            } elseif (Auth::user()->role === 'manager') {
                return redirect()->route('manager.dashboard');
            } elseif (Auth::user()->role === 'applicant') {
                return redirect()->route('applicant.dashboard');
            } else {
                return redirect()->route('applicant.dashboard');
            }
        } else {
            return redirect()->route('login')
                ->withErrors(['login' => 'Kredensial login tidak valid. Silakan coba lagi.'])
                ->withInput();
        }
    }

    public function authenticated($user)
    {
        Session::put('user_id', $user->uuid);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}

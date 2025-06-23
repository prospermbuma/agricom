<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\ActivityLogService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();


        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            activity()
                ->causedBy(Auth::user())
                ->withProperties(['action' => 'user_login', 'ip_address' => request()->ip()])
                ->log('User ' . Auth::user()->name . ' logged in.');

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');

        // Don't override validation errors — just show login failure
        // return back()->withErrors([
        //     'email' => 'The provided credentials do not match our records.',
        // ])->withInput();

    }

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        activity()
            ->causedBy($user)
            ->withProperties(['action' => 'user_registered', 'ip_address' => request()->ip()])
            ->log('New user registered: ' . $user->name);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        activity()
            ->causedBy(Auth::user())
            ->withProperties(['action' => 'user_logout', 'ip_address' => request()->ip()])
            ->log('User ' . Auth::user()->name . ' logged out.');

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

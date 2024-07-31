<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Quiz\Quiz;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
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
        $request->authenticate();

        $request->session()->regenerate();

        if (\Auth::user()->can('participant')) {
            return redirect(route('lk.dashboard', absolute: false));
        } else if (\Auth::user()->can('admin-users')) {
            return redirect(route('admin.user.index', absolute: false));
        } else if (\Auth::user()->can('admin-site')) {
            return redirect(route('admin.widgets.index', absolute: false));
        } else if (\Auth::user()->can('admin-quiz') ||
            \Auth::user()->hasAnyRoleOf(Quiz::class)) {
            return redirect(route('admin.quiz.index', absolute: false));
        } else {
            return redirect()->route('admin.user.edit', Auth::user());
            // return redirect(route('dashboard', absolute: false));
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

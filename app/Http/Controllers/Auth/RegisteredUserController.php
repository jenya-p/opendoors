<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Content\FaqCategory;
use App\Models\Content\Schedule;
use App\Models\Dir\Citizenship;
use App\Models\EduLevel;
use App\Models\Profile;
use App\Models\Track;
use App\Models\University;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller {
    /**
     * Display the registration view.
     */
    public function create() {

        return view('pages.register', [
            'edu_level_options' => EduLevel::get()->translate(),
            'citizenship_options' => Citizenship::all(['id', 'name', 'name_en', 'code'])->translate(),
            'locale_options' => \Arr::assocToOptions(['ru' => 'Русский', 'en' => 'English']),
            'sex_options' => \Arr::assocToOptions(['m' => trans('Male'), 'f' => trans('Female')]),
            'track_options' => Track::active()->get(['id', 'name', 'name_en'])->translate(),
            'profile_options' => Profile::active()->get(['id', 'name', 'name_en'])->translate(),
        ]);
//
//        return Inertia::render('Auth/Register', [
//            'user' => Auth::user()
//        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse {
        $request->validate([
            'confirm' => 'accepted',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'name' => 'required|string|max:255',
            'password' => ['required', 'confirmed', Rules\Password::min(6)],
        ], [
            'accepted' => 'Вы должны принять пользовательское соглашение',
            'password.min' => 'Количество символов в пароле должно быть не меньше :min.'
        ]);

        $user = User::create([
            'name' => '',
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ParticipantRegistrationRequest;
use App\Models\Content\FaqCategory;
use App\Models\Content\Schedule;
use App\Models\Dir\Citizenship;
use App\Models\EduLevel;
use App\Models\Participant\Member;
use App\Models\Participant\Participant;
use App\Models\Profile;
use App\Models\Scopes\Active;
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

        EduLevel::addGlobalScope(Active::class);
        Profile::addGlobalScope(Active::class);
        Track::addGlobalScope(Active::class);

        return view('pages.register', [
            'edu_level_options' => EduLevel::get()->translate(),
            'citizenship_options' => Citizenship::all(['id', 'name', 'name_en', 'code'])->translate(),
            'locale_options' => \Arr::assocToOptions(['ru' => 'Русский', 'en' => 'English']),
            'sex_options' => \Arr::assocToOptions(['m' => trans('Male'), 'f' => trans('Female')]),
            'track_options' => Track::get(['id', 'name', 'name_en'])
                ->append('required_edu_level_ids')
                ->translate(),
            'profile_options' => Profile::get(['id', 'name', 'name_en'])->translate(),
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
    public function store(ParticipantRegistrationRequest $request): RedirectResponse {

        $user = User::create([
            'name' => $request->last_name . ' ' . $request->first_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'locale' => $request->getLocale('locale'),
        ]);

        $participant = $user->participants()
            ->create($request->only(['citizenship_id','last_name','first_name','sex','birthdate','phone','email']));

        $participant->edu_level_ids = $request->edu_levels;

        foreach ($participant->members as $memberData){
            $member = $participant->members()->create($memberData);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}

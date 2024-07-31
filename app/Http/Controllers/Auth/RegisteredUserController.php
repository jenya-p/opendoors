<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ParticipantRegistrationRequest;
use App\Http\Requests\Lk\ParticipantRequest;
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

        return view('pages.register', $this->getFormOptions());

    }


    public function edit(Request $request) {
        $user = Auth()->user();
        /** @var Participant $item */
        $item = $user->participant;
        $item->load([
            'members:id,participant_id,profile_id,track_id',
        ])->append('edu_level_ids');
        $item->email = $user->email;
        $item->locale = $user->locale;

        return Inertia::render('Lk/Participant',
            $this->getFormOptions() + [
                'item' => $item
            ]
        );
    }

    public function getFormOptions() {
        return [
            'edu_level_options' => EduLevel::get()->translate(),
            'citizenship_options' => Citizenship::all(['id', 'name', 'name_en', 'code'])->translate(),
            'locale_options' => \Arr::assocToOptions(['ru' => 'Русский', 'en' => 'English']),
            'sex_options' => \Arr::assocToOptions(['m' => trans('Male'), 'f' => trans('Female')]),
            'track_options' => Track::get(['id', 'name', 'name_en'])
                ->append('required_edu_level_ids')
                ->translate(),
            'profile_options' => Profile::get(['id', 'name', 'name_en'])->translate(),
        ];
    }



    public function store(ParticipantRegistrationRequest $request) {

        $user = User::create([
            'name' => $request->last_name . ' ' . $request->first_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'locale' => $request->get('locale'),
        ]);

        $participant = $user->participants()
            ->create($request->only(['citizenship_id', 'last_name', 'first_name', 'sex', 'birthdate', 'phone', 'email']));

        $participant->edu_level_ids = $request->edu_level_ids;
        $participant->save();

        foreach ($request->members as $memberData) {
            $member = $participant->members()->create($memberData);
        }

        event(new Registered($user));

        Auth::login($user);

        if(\request()->isXmlHttpRequest()){
            return ['result' => 'ok'];
        } else {
            return redirect(route('lk.dashboard', absolute: false));
        }

    }



    public function update(ParticipantRequest $request): RedirectResponse {


        /** @var User $user */
        $user = Auth::user();
        $user->update([
            'name' => $request->last_name . ' ' . $request->first_name,
            'email' => $request->email,
            'locale' => $request->get('locale'),
        ]);


        $user->participant
            ->update($request->only(['citizenship_id', 'last_name', 'first_name', 'sex', 'birthdate', 'phone', 'email', 'edu_level_ids']));

        $savedMembers = $user->participant->members()->get(['id', 'track_id','profile_id'])->toArray();

        foreach ($request->members as $requestItem) {
            $finded = false;
            foreach ($savedMembers as $index => $savedItem){
                if($savedItem['track_id'] == $requestItem['track_id'] && $savedItem['profile_id'] == $requestItem['profile_id']){
                    $finded = true;
                    unset($savedMembers[$index]);
                    break;
                }
            }
            if(!$finded){
                $user->participant->members()->create([
                    'track_id' => $requestItem['track_id'],
                    'profile_id' => $requestItem['profile_id']
                ]);
            }
        }
        foreach ($savedMembers as $index => $savedItem){
            Member::find($savedItem['id'])->delete();
        }

        return redirect()->back()->with('message', _('Анкета участника сохранена'));
    }


}

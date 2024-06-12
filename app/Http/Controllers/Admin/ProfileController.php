<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Models\Profile;
use Inertia\Inertia;

class ProfileController extends Controller {

    public function index() {
        return Inertia::render('Admin/Profile/Index', [
            'items' => Profile::all()
        ]);
    }


    public function create() {
         return Inertia::render('Admin/Profile/Edit', ['item' => new Profile()]);
    }


    public function store(ProfileRequest $request) {
        $profile = Profile::create($request->validated());
        return \Redirect::route('admin.profile.index');
    }

    public function edit(Profile $profile) {
        return Inertia::render('Admin/Profile/Edit', [
            'item' => $profile,
        ]);
    }


    public function update(ProfileRequest $request, Profile $profile) {
        $profile->update($request->validated());
        return \Redirect::route('admin.profile.index');
    }


    public function destroy(Profile $profile) {
        $profile->delete();
        return ['result' => 'ok'];
    }
}

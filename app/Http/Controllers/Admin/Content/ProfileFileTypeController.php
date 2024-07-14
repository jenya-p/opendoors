<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\Content\ProfileFileTypeRequest;
use App\Models\Profile;
use App\Models\ProfileFile;
use App\Models\ProfileFileType;
use App\Models\Track;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ProfileFileTypeController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $items = ProfileFileType::with('track:id,name')->get()->append(['type_name', 'file_count', 'can_delete']);
        return Inertia::render('Admin/Content/ProfileFileType/Index', [
            'items' => $items
        ]);
    }


    public function create() {
        return Inertia::render('Admin/Content/ProfileFileType/Edit', [
            'track_options' => fn() => Track::all('id', 'name')->toArray(),
            'type_options' => ProfileFileType::TYPES,
            'item' => new \App\Models\ProfileFileType()
        ]);
    }


    public function store(ProfileFileTypeRequest $request) {
        $profileFileType = ProfileFileType::create($request->validated());

        return \Redirect::route('admin.profile-file-type.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProfileFileType $profileFileType) {
        return Inertia::render('Admin/Content/ProfileFileType/Edit', [
            'track_options' => fn() => Track::all('id', 'name')->toArray(),
            'type_options' => ProfileFileType::TYPES,
            'item' => $profileFileType
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProfileFileTypeRequest $request, ProfileFileType $profileFileType) {
        $profileFileType->update($request->validated());
        return \Redirect::route('admin.profile-file-type.index');
    }

    public function destroy(ProfileFileType $profileFileType) {
        if($profileFileType->can_delete){
            $profileFileType->delete();
            return ['result' => 'ok'];
        } else {
            return response(['message' => 'Нельзя удалить этот объект'], 500);
        }
    }

}

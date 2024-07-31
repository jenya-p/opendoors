<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TrackRequest;
use App\Models\EduLevel;
use App\Models\Track;
use Inertia\Inertia;

class TrackController extends Controller {

    public function index() {
        $items = Track::all();
        $items->append('max_edu_level');
        return Inertia::render('Admin/Track/Index', [
            'items' => $items
        ]);
    }


    public function create() {
        $item = new Track();
        return Inertia::render('Admin/Track/Edit', [
            'edu_level_options' => EduLevel::get(['id', 'name'])->toArray(),
            'item' => $item
        ]);
    }


    public function store(TrackRequest $request) {
        $track = Track::create($request->validated());
        return \Redirect::route('admin.track.index');
    }

    public function edit(Track $track) {
        $track->append('required_edu_level_ids');
        return Inertia::render('Admin/Track/Edit', [
            'edu_level_options' => EduLevel::get(['id', 'name'])->toArray(),
            'item' => $track,
        ]);
    }


    public function update(TrackRequest $request, Track $track) {
        $track->update($request->validated());
        return \Redirect::route('admin.track.index');
    }


    public function destroy(Track $track) {
        $track->delete();
        return ['result' => 'ok'];
    }
}

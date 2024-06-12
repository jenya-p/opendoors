<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TrackRequest;
use App\Models\Track;
use Inertia\Inertia;

class TrackController extends Controller {

    public function index() {
        return Inertia::render('Admin/Track/Index', [
            'items' => Track::all()
        ]);
    }


    public function create() {
         return Inertia::render('Admin/Track/Edit', ['item' => new Track()]);
    }


    public function store(TrackRequest $request) {
        $track = Track::create($request->validated());
        return \Redirect::route('admin.track.index');
    }

    public function edit(Track $track) {
        return Inertia::render('Admin/Track/Edit', [
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

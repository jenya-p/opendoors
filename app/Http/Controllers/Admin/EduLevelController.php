<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EduLevelRequest;
use App\Models\EduLevel;
use Inertia\Inertia;

class EduLevelController extends Controller {

    public function index() {
        return Inertia::render('Admin/EduLevel/Index', [
            'items' => EduLevel::all()
        ]);
    }


    public function create() {
         return Inertia::render('Admin/EduLevel/Edit', ['item' => new EduLevel()]);
    }


    public function store(EduLevelRequest $request) {
        $eduLevel = EduLevel::create($request->validated());
        return \Redirect::route('admin.edu-level.index');
    }

    public function edit(EduLevel $eduLevel) {
        return Inertia::render('Admin/EduLevel/Edit', [
            'item' => $eduLevel,
        ]);
    }


    public function update(EduLevelRequest $request, EduLevel $eduLevel) {
        $eduLevel->update($request->validated());
        return \Redirect::route('admin.edu-level.index');
    }


    public function destroy(EduLevel $eduLevel) {
        $eduLevel->delete();
        return ['result' => 'ok'];
    }
}

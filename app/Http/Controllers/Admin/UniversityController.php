<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UniversityRequest;
use App\Models\University;
use Inertia\Inertia;

class UniversityController extends Controller {

    public function index() {
        return Inertia::render('Admin/University/Index', [
            'items' => University::all()
        ]);
    }


    public function create() {
         return Inertia::render('Admin/University/Edit', ['item' => new University()]);
    }


    public function store(UniversityRequest $request) {
        $university = University::create($request->validated());
        return \Redirect::route('admin.university.index');
    }

    public function edit(University $university) {
        return Inertia::render('Admin/University/Edit', [
            'item' => $university,
        ]);
    }


    public function update(UniversityRequest $request, University $university) {
        $university->update($request->validated());
        return \Redirect::route('admin.university.index');
    }


    public function destroy(University $university) {
        $university->delete();
        return ['result' => 'ok'];
    }
}

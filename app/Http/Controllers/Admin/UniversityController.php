<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UniversityRequest;
use App\Models\EduLevel;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class UniversityController extends Controller {

    public function index() {
        return Inertia::render('Admin/University/Index', [
            'items' => University::all()
        ]);
    }


    public function create() {
        $item = new University();
        $item->setRelation('logo', []);
        $item->setRelation('logo_en', []);
         return Inertia::render('Admin/University/Edit', ['item' => new University()]);
    }


    public function store(UniversityRequest $request) {
        $university = University::create($request->validated());
        return \Redirect::route('admin.university.index');
    }

    public function edit(University $university) {
        $university->load('logo', 'logo_en');
        return Inertia::render('Admin/University/Edit', [
            'item' => $university,
        ]);
    }

    public function update(UniversityRequest $request, University $university) {
        $university->update($request->validated());
        return \Redirect::route('admin.university.index');
    }

    public function status(University $university, Request $request) {
        $data = $request->validate([
            'status' => ['required', Rule::in('active', 'disabled')]
        ]);
        $university->update($data);
        return [
            'result' => 'ok',
            'item' => $university
        ];
    }

    public function updateOrder(Request $request) {
        $request->validate([
            'orders' => ['required', 'array'],
            'orders.*' => ['required', 'integer', 'exists:universities,id'],
        ]);

        foreach ($request->orders as $index => $universityId){
            University::find($universityId)->update([
                'order' => (int)$index + 1
            ]);
        }

        return [
            'result' => 'ok',
            'items' => University::all()
        ];

    }

    public function destroy(University $university) {
        $university->delete();
        return ['result' => 'ok',
            'items' => University::all()
        ];
    }
}

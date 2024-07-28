<?php

namespace App\Http\Controllers\Admin\Dir;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dir\CitizenshipRequest;
use App\Models\Dir\Citizenship;
use Inertia\Inertia;

class CitizenshipController extends Controller {

    public function index() {

        $items = Citizenship::all();

        return Inertia::render('Admin/Dir/Citizenship/Index', [
            'items' => $items
        ]);
    }


    public function create() {
         return Inertia::render('Admin/Dir/Citizenship/Edit', ['item' => new Citizenship()]);
    }


    public function store(CitizenshipRequest $request) {
        $citizenship = Citizenship::create($request->validated());
        return \Redirect::route('admin.dir-citizenship.index');
    }

    public function edit(Citizenship $citizenship) {
        return Inertia::render('Admin/Dir/Citizenship/Edit', [
            'item' => $citizenship,
        ]);
    }


    public function update(CitizenshipRequest $request, Citizenship $citizenship) {
        $citizenship->update($request->validated());
        return \Redirect::route('admin.dir-citizenship.index');
    }


    public function destroy(Citizenship $citizenship) {
        $citizenship->delete();
        return ['result' => 'ok'];
    }
}

<?php

namespace App\Http\Controllers\Admin\Dir;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dir\RegionRequest;
use App\Models\Dir\Region;
use Inertia\Inertia;

class RegionController extends Controller {

    public function index() {

        $items = Region::all();

        return Inertia::render('Admin/Dir/Region/Index', [
            'items' => $items
        ]);
    }


    public function create() {
         return Inertia::render('Admin/Dir/Region/Edit', ['item' => new Region()]);
    }


    public function store(RegionRequest $request) {
        $region = Region::create($request->validated());
        return \Redirect::route('admin.dir-region.index');
    }

    public function edit(Region $region) {
        return Inertia::render('Admin/Dir/Region/Edit', [
            'item' => $region,
        ]);
    }


    public function update(RegionRequest $request, Region $region) {
        $region->update($request->validated());
        return \Redirect::route('admin.dir-region.index');
    }


    public function destroy(Region $region) {
        $region->delete();
        return ['result' => 'ok'];
    }
}

<?php

namespace App\Http\Controllers\Admin\Dir;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dir\CountryRequest;
use App\Models\Dir\Country;
use App\Models\Dir\Region;
use Inertia\Inertia;

class CountryController extends Controller {

    public function index() {

        $items = Country::all();

        return Inertia::render('Admin/Dir/Country/Index', [
            'items' => $items
        ]);
    }


    public function create() {
         return Inertia::render('Admin/Dir/Country/Edit', [
             'region_options' => Region::get(['id', 'name'])->toArray(),
             'item' => new Country()
         ]);
    }


    public function store(CountryRequest $request) {
        $country = Country::create($request->validated());
        return \Redirect::route('admin.dir-country.index');
    }

    public function edit(Country $country) {
        return Inertia::render('Admin/Dir/Country/Edit', [
            'region_options' => Region::get(['id', 'name'])->toArray(),
            'item' => $country,
        ]);
    }


    public function update(CountryRequest $request, Country $country) {
        $country->update($request->validated());
        return \Redirect::route('admin.dir-country.index');
    }


    public function destroy(Country $country) {
        $country->delete();
        return ['result' => 'ok'];
    }
}

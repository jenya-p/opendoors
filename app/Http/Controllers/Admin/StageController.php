<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StageRequest;
use App\Models\Profile;
use App\Models\Stage;
use App\Models\Track;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Inertia\Inertia;

class StageController extends Controller {

    public function index(Request $request) {

        $query = Stage::with('profile:id,name', 'track:id,name');

        $filter = $request->only('track_id', 'profile_id', 'type');
        $query->filter($filter);

        if (!empty($request->sort)) {
            list($name, $dir) = explode(':', $request->sort);
            if($name == 'profile'){
                $query
                    ->leftJoin('profiles', 'profiles.id', '=', 'stages.profile_id')
                    ->orderBy('profiles.name', $dir);
            } else if($name == 'track'){
                $query
                    ->leftJoin('tracks', 'tracks.id', '=', 'stages.track_id')
                    ->orderBy('tracks.name', $dir);
            } else {
                $query->orderBy($name, $dir);
            }

        }

        /** @var LengthAwarePaginator $items */
        $items = $query->paginate(50);

        $items->tap(fn($item) => $item->append('type_name'));

        if(!$request->inertia() && $request->isXmlHttpRequest()){
            return [
                'filter' => $filter,
                'items' => $items,
            ];
        } else {
            return Inertia::render('Admin/Stage/Index', [
                'filter' => $filter,
                'items' => $items,
                'profile_options' => fn() => Profile::all('id', 'name')->toArray(),
                'track_options' => fn() => Track::all('id', 'name')->toArray(),
                'type_options' => function () {
                    $options = [];
                    foreach (Stage::TYPES as $id => $name) {
                        $options[] = ['id' => $id, 'name' => $name];
                    }
                    return $options;
                }
            ]);
        }



    }



    public function edit(Stage $stage) {
        $stage->load('profile:id,name', 'track:id,name')->append('type_name');
        return Inertia::render('Admin/Stage/Edit', [
            'item' => $stage,
            'profile_options' => fn() => Profile::all('id', 'name')->toArray(),
            'track_options' => fn() => Track::all('id', 'name')->toArray(),
            'type_options' => function () {
                $options = [];
                foreach (Stage::TYPES as $id => $name) {
                    $options[] = ['id' => $id, 'name' => $name];
                }
                return $options;
            }
        ]);
    }


    public function update(StageRequest $request, Stage $stage) {
        $stage->update($request->validated());
        return \Redirect::route('admin.stage.index');
    }


    public function destroy(Stage $stage) {
        $stage->delete();
        return ['result' => 'ok'];
    }
}

<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\Content\ProfileFileRequest;
use App\Models\Profile;
use App\Models\ProfileFile;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class ProfileFileController extends Controller {

    public function index(Request $request) {

        $query = ProfileFile::query();

        $filter = trim($request->filter);
        if (!empty($filter)) {
            $lcQuery = '%' . mb_strtolower(trim($filter)) . '%';
            $query->whereIn('profile_id', function (Builder $subQuery) use ($lcQuery) {
                return $subQuery->select('id')->from('profiles')
                    ->whereRaw('profiles.name like ?', $lcQuery);
            });
        }

        if (!empty($request->sort)) {
            list($name, $dir) = explode(':', $request->sort);
            $query->orderBy($name, $dir);
        }
        $query->orderBy('id', 'asc');
        $items = $query->paginate(50);

        return Inertia::render('Admin/Content/ProfileFile/Index', [
            'items' => $items
        ]);
    }


    public function create() {
         return Inertia::render('Admin/Content/ProfileFile/Edit', [
            'item' => new ProfileFile(),
            'profile_options' => fn() => Profile::all('id', 'name')->toArray(),
         ]);
    }


    public function store(ProfileFileRequest $request) {
        $profileFile = ProfileFile::create($request->validated());
        return \Redirect::route('admin.profileFile.index');
    }

    public function edit(ProfileFile $profileFile) {
        return Inertia::render('Admin/Content/ProfileFile/Edit', [
            'item' => $profileFile,
            'profile_options' => fn() => Profile::all('id', 'name')->toArray(),
        ]);
    }


    public function update(ProfileFileRequest $request, ProfileFile $profileFile) {
        $profileFile->update($request->validated());
        return \Redirect::route('admin.profileFile.index');
    }


    public function destroy(ProfileFile $profileFile) {
        $profileFile->delete();
        return ['result' => 'ok'];
    }
}

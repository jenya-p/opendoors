<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Models\Attachment;
use App\Models\Profile;
use App\Models\ProfileFile;
use App\Models\ProfileFileType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ProfileController extends Controller {

    public function index() {
        return Inertia::render('Admin/Profile/Index', [
            'items' => Profile::all()
        ]);
    }


    public function create() {
        $item = new Profile();
        $item->setRelation('files', []);
        return Inertia::render('Admin/Profile/Edit', [
            'item' => $item,
            'file_types' => fn() => ProfileFileType::get(['id', 'name'])->toArray(),
            'file_type_types' => ProfileFileType::TYPES
        ]);
    }


    public function store(ProfileRequest $request) {
        $profile = Profile::create($request->validated());
        $this->updateProfileFiles($profile, $request->get('files'));
        return \Redirect::route('admin.profile.index');
    }

    public function edit(Profile $profile) {
        $profile->load('files', 'files.file', 'files.file_en');
        return Inertia::render('Admin/Profile/Edit', [
            'item' => $profile,
            'file_types' => fn() => ProfileFileType::with('tracks:id,name')->get(['id', 'type', 'name'])->toArray(),
            'file_type_types' => ProfileFileType::TYPES
        ]);
    }

    public function update(ProfileRequest $request, Profile $profile) {
        $profile->update($request->validated());
        $this->updateProfileFiles($profile, $request->get('files'));
        return \Redirect::route('admin.profile.index');
    }

    public function updateProfileFiles($profile, $files) {

        foreach ($files as $file) {
            $dFile = null;
            if (!empty($file['id'])) {
                /** @var ProfileFile $dFile */
                $dFile = ProfileFile::find($file['id']);
            }
            if (empty($file['file_en_id']) && empty($file['file_id'])) {
                if (!empty($dFile)) {
                    $dFile->delete();
                }
            } else {
                $fileData = [
                    'profile_id' => $profile->id,
                    'type_id' => $file['type_id'],
                    'status' => $file['active'] ? 'active' : 'disabled',
                ];
                if (empty($dFile)) {
                    $dFile = ProfileFile::create($fileData);
                } else {
                    $dFile->update($fileData);
                }

                foreach (['file_en_id' => 'en', 'file_id' => 'ru'] as $field => $attaType) {
                    if (!empty($file[$field])) {
                        $atta = Attachment::find($file[$field]);
                        if ($atta) {
                            $atta->update([
                                'item_type' => 'profile_file',
                                'item_id' => $dFile->id,
                                'type' => $attaType
                            ]);
                        }
                    }
                }
            }
        }
    }


    public function status(Profile $profile, Request $request) {
        $data = $request->validate([
            'status' => ['required', Rule::in('active', 'disabled')]
        ]);
        $profile->update($data);
        return [
            'result' => 'ok',
            'item' => $profile
        ];
    }

    public function destroy(Profile $profile) {
        $profile->delete();
        return ['result' => 'ok'];
    }
}

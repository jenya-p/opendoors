<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dir\Citizenship;
use App\Models\EduLevel;
use App\Models\Participant\Participant;
use App\Models\Profile;
use App\Models\Track;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ParticipantController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {

        $query = Participant::query()->with([
            'user:id,name,email',
            'eduLevels:id,name',
            'citizenship:id,name',
            'members:participant_id,profile_id,track_id',
            'members.profile:id,name',
            'members.track:id,name',
        ]);

        $filter = trim($request->filter);
        if (!empty($filter)) {
            $lcQuery = '%' . mb_strtolower(trim($filter)) . '%';
            $query->whereIn('user_id', function (Builder $subQuery) use ($lcQuery) {
                return $subQuery->select('id')->from('users')
                    ->whereRaw('(users.name like ? or users.email like ?)', [$lcQuery, $lcQuery]);
            });
        }

        if (!empty($request->sort)) {
            list($name, $dir) = explode(':', $request->sort);
            $query->orderBy($name, $dir);
        }
        $query->orderBy('id', 'asc');
        $items = $query->paginate(50);

        return Inertia::render('Admin/Participant/Index', ['items' => $items]);
    }

    public function create() {
        return Inertia::render('Admin/Participant/Edit', [
            'citizenship_options' => Citizenship::all(['id', 'name'])->toArray(),
            'edu_level_options' => EduLevel::all(['id', 'name'])->toArray(),
            'locale_options' => \Arr::assocToOptions(['ru' => 'Русский', 'en' => 'Английский']),
            'sex_options' => \Arr::assocToOptions(['m' => "Мужской", 'f' => "Женский"]),
        ]);
    }

    public function edit(Participant $participant) {
        $participant->append('edu_level_ids');
        $participant->load(
            'user:id,name,email',
            'eduLevels:id,name',
            'members:participant_id,profile_id,track_id',
            'members.profile:id,name',
            'members.track:id,name',
        );
        return Inertia::render('Admin/Participant/Edit', [
            'citizenship_options' => Citizenship::all(['id', 'name'])->toArray(),
            'edu_level_options' => EduLevel::all(['id', 'name'])->toArray(),
            'locale_options' => \Arr::assocToOptions(['ru' => 'Русский', 'en' => 'Английский']),
            'sex_options' => \Arr::assocToOptions(['m' => "Мужской", 'f' => "Женский"]),
            'item' => $participant,
        ]);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'locale' => ['required', Rule::in('ru', 'en')],

            'citizenship_id' => 'required|integer|exists:dir_citizenships,id',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'sex' => ['required', Rule::in('m', 'f')],
            'birthdate' => 'required|date_format:Y-m-d',
            'phone' => 'required|string|max:32|min:4|unique:participants,phone',

            'edu_level_ids' => 'nullable|array',
            'edu_level_ids.*' => 'required|exists:edu_levels,id',
        ]);
        $participant = Participant::create($request->except('edu_level_ids'));
        $participant->edu_level_ids = $request->edu_level_ids;
        $participant->save();

        return \Redirect::route('admin.participant.index');
    }

    public function update(Participant $participant, Request $request) {
        $data = $request->validate([
            'citizenship_id' => 'required|integer|exists:dir_citizenships,id',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'sex' => ['required', Rule::in('m', 'f')],
            'birthdate' => 'required|date_format:Y-m-d',
            'phone' => 'required|string|max:32|min:4|unique:participants,phone,' . $participant->id,
            'edu_level_ids' => 'nullable|array',
            'edu_level_ids.*' => 'required|exists:edu_levels,id',
        ]);

        $participant->update($data);
        return \Redirect::route('admin.participant.index');

    }


    public function destroy(Participant $participant) {
        $participant->delete();
        return ['result' => 'ok'];
    }


}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EduLevel;
use App\Models\Profile;
use App\Models\Student;
use App\Models\Track;
use App\Models\University;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class StudentController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {

        $query = Student::query()->with([
            'user:id,name,email',
            'eduLevel:id,name',
            'profiles:student_id,profile_id,track_id',
            'profiles.profile:id,name',
            'profiles.track:id,name',
        ]);

        $filter = trim($request->filter);
        if (!empty($filter)) {
            $lcQuery = '%' . mb_strtolower(trim($filter)) . '%';
            $query->whereIn('user_id', function (Builder $subQuery) use ($lcQuery) {
                return $subQuery->select('id')->from('users')
                    ->whereRaw('users.name like ? or users.email like ?', [$lcQuery, $lcQuery]);
            });
        }

        if (!empty($request->sort)) {
            list($name, $dir) = explode(':', $request->sort);
            $query->orderBy($name, $dir);
        }
        $query->orderBy('id', 'asc');
        $items = $query->paginate(50);

        return Inertia::render('Admin/Student/Index', ['items' => $items]);
    }

    public function create() {
        return Inertia::render('Admin/Student/Edit', [
            'profile_options' => Profile::all(['id', 'name'])->toArray(),
            'track_options' => Track::all(['id', 'name'])->toArray(),
            'edu_level_options' => EduLevel::all(['id', 'name'])->toArray(),
        ]);
    }

    public function edit(Student $student) {
        $student->load(
            'user:id,name,email',
            'eduLevel:id,name',
            'profiles:student_id,profile_id,track_id',
            'profiles.profile:id,name',
            'profiles.track:id,name',
        );
        return Inertia::render('Admin/Student/Edit', [
            'profile_options' => Profile::all('id', 'name')->toArray(),
            'track_options' => Track::all('id', 'name')->toArray(),
            'edu_level_options' => EduLevel::all(['id', 'name'])->toArray(),
            'item' => $student,
        ]);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'edu_level_id'  => 'required|integer|exists:edu_levels,id',
            'citizenship' => 'required|string',
        ]);


        $student = Student::create($data);

        return \Redirect::route('admin.student.index');
    }

    public function update(Student $student, Request $request) {
        $data = $request->validate([
            'edu_level_id'  => 'required|integer|exists:edu_levels,id',
            'citizenship' => 'required|string',
        ]);

        $student->update($data);
        return \Redirect::route('admin.student.index');

    }


    public function destroy(Student $student) {
        $student->delete();
        return ['result' => 'ok'];
    }


}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UniversityUser;
use App\Models\University;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class UniversityUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request){

        $query = UniversityUser::query()->with('user:id,name,email', 'university:id,name');

        $filter = trim($request->filter);
        if(!empty($filter)){
            $lcQuery = '%' . mb_strtolower(trim($filter)) . '%';

            $query->where(function(\Illuminate\Database\Eloquent\Builder $query) use ($lcQuery){
                $query->whereIn('user_id', function(Builder $subQuery) use ($lcQuery){
                    return $subQuery->select('id')->from('users')
                        ->whereRaw('users.name like ? or users.email like ?', [$lcQuery,$lcQuery]);
                })->orWhereIn('university_id', function(Builder $subQuery) use ($lcQuery) {
                    return $subQuery->select('id')->from('universities')
                        ->whereRaw('universities.name like ?', [$lcQuery]);
                });
            });

        }

        if(!empty($request->sort)){
            list($name, $dir) = explode(':', $request->sort);
            $query->orderBy($name, $dir);
        }

        $query->orderBy('id', 'asc');
        $items = $query->paginate(50);
        $items->tap(function($itm){
            $itm->append('role_names');
        });

        return Inertia::render('Admin/UniversityUser/Index', ['items' => $items]);
    }

    public function create() {
        return Inertia::render('Admin/UniversityUser/Edit', [
            'university_options' => University::all('id', 'name')->toArray(),
            'role_options' => UniversityUser::ROLES
        ]);
    }

    public function edit(UniversityUser $universityUser) {
        $universityUser->load('user:id,name,email', 'university:id,name');
        return Inertia::render('Admin/UniversityUser/Edit', [
            'role_options' => UniversityUser::ROLES,
            'item' => $universityUser,
        ]);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'university_id' => 'required|integer|exists:universities,id',
            'roles' => 'required|array',
            'roles.*' => ['required', Rule::in(array_keys(UniversityUser::ROLES))],
        ]);

        /** @var UniversityUser $universityUser */
        $universityUser = UniversityUser::withTrashed()
            ->where('user_id', '=', $data['user_id'])
            ->where('university_id', '=', $data['university_id'])->first();

        if(!empty($universityUser)){
            if(!empty($universityUser->deleted_at)){
                $universityUser->restore();
                $universityUser->update($data);
            } else {
                throw ValidationException::withMessages([
                    'user_id' => 'Этот аккаунт уже имеет профиль представителя'
                ]);
            }
        } else {
            $universityUser = UniversityUser::create($data);
        }

        return \Redirect::route('admin.university-user.index');
    }

    public function update(UniversityUser $universityUser, Request $request) {
        $data = $request->validate([
            'roles' => 'required|array',
            'roles.*' => ['required', Rule::in(array_keys(UniversityUser::ROLES))],
        ]);

        $universityUser->update($data);
        return \Redirect::route('admin.university-user.index');

    }


    public function destroy(UniversityUser $universityUser) {
        $universityUser->delete();
        return ['result' => 'ok'];
    }


}

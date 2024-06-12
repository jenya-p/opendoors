<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accident;
use App\Models\Admin;
use App\Models\UniversityUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $query = User::query();
        $filter = trim($request->filter);
        if(!empty($filter)){
            $lcQuery = '%' . mb_strtolower(trim($filter)) . '%';
            $query->whereRaw('name like ? or email like ?', [$lcQuery,$lcQuery]);
        }

        if(!empty($request->sort)){
            list($name, $dir) = explode(':', $request->sort);
            $query->orderBy($name, $dir);
        }
        $query->orderBy('id', 'asc');

        $items = $query->paginate(50);
        $items->tap(fn($itm) => $itm->append('role_names'));
        return Inertia::render('Admin/User/Index', ['items' => $items]);
    }

    public function create() {
        return Inertia::render('Admin/User/Edit', [
            'admin_roles' => Admin::ROLES,
            'university_roles' => UniversityUser::ROLES,
        ]);
    }

    public function edit(User $user) {
        return Inertia::render('Admin/User/Edit', [
            'admin_roles' => Admin::ROLES,
            'university_roles' => UniversityUser::ROLES,
            'item' => $user,
        ]);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required|string|max:256|min:4',
            'email' => [
                'required', 'email',
                Rule::unique('users', 'email'),
            ],
            'password' => ['required', 'min:6', 'confirmed'],
            'password_confirmation' => ['required', 'min:6'],
        ]);

        $data['password'] = \Hash::make($data['password']);
        $user = User::create($data);

        return \Redirect::route('admin.user.index');
    }

    public function update(User $user, Request $request) {

        $data = $request->validate([
            'name' => 'required|string|max:256|min:4',
            'email' => [
                'required', 'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ]
        ]);

        $user->update($data);
        return \Redirect::route('admin.user.index');

    }


    public function password(Request $request) {

        $request->validate([
            'old_password' => ['required', 'min:6', function ($attribute, $value, $fail) {
                if (!Hash::check($value, auth()->user()->password)) {
                    return $fail('Неверный пароль');
                }}],
            'password' => ['required', 'min:6', 'confirmed', 'different:old_password'],
            'password_confirmation' => ['required', 'min:6'],
        ]);

        auth()->user()->update(['password' => Hash::make($request->get('password'))]);

        return \Redirect::route('admin.user.edit', ['user' => \Auth::id()]);

    }

    public function suggest(Request $request){
        $query = User::query();
        $filter = trim($request->get('query'));
        if(!empty($filter)){
            $lcQuery = '%' . mb_strtolower($filter) . '%';
            $query->whereRaw('name like ? or email like ?', [$lcQuery,$lcQuery]);
        }

        $query->orderBy('name', 'asc')->limit(10);
        return $query->get(['id', 'name', 'email']);
    }

}

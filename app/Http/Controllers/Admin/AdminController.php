<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\University;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request){

        $query = Admin::query()->with('user:id,name,email');

        $filter = trim($request->filter);
        if(!empty($filter)){
            $lcQuery = '%' . mb_strtolower(trim($filter)) . '%';
            $query->whereIn('id', function(Builder $subQuery) use ($lcQuery){
                return $subQuery->select('id')->from('users')
                    ->whereRaw('(users.name like ? or users.email like ?)', [$lcQuery,$lcQuery]);
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

        return Inertia::render('Admin/Admin/Index', ['items' => $items]);
    }

    public function create() {
        return Inertia::render('Admin/Admin/Edit', [
            'role_options' => Admin::ROLES
        ]);
    }

    public function edit(Admin $admin) {
        $admin->load('user:id,name,email');
        return Inertia::render('Admin/Admin/Edit', [
            'role_options' => Admin::ROLES,
            'item' => $admin,
        ]);
    }

    public function store(Request $request) {
        $data = $request->validate([
            'id' => 'required|integer|exists:users,id',
            'roles' => 'required|array',
            'roles.*' => ['required', Rule::in(array_keys(Admin::ROLES))],
        ]);

        /** @var Admin $admin */
        $admin = Admin::withTrashed()->find($data['id']);
        if(!empty($admin)){
            if(!empty($admin->deleted_at)){
                $admin->restore();
                $admin->update($data);
            } else {
                throw ValidationException::withMessages([
                    'id' => 'Этот аккаунт уже имеет профиль администратора'
                ]);
            }

        } else {
            $admin = Admin::create($data);
        }

        return \Redirect::route('admin.admin.index');
    }

    public function update(Admin $admin, Request $request) {
        $data = $request->validate([
            'roles' => 'required|array',
            'roles.*' => ['required', Rule::in(array_keys(Admin::ROLES))],
        ]);

        $admin->update($data);
        return \Redirect::route('admin.admin.index');

    }


    public function destroy(Admin $admin) {
        $admin->delete();
        return ['result' => 'ok'];
    }


}

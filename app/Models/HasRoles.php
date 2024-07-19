<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasRelationships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Ramsey\Collection\Collection;

/**
 * @property-read Role $roles
 */
trait HasRoles {

    public function roles() {
        return $this->morphMany(Role::class, 'item');
    }

    public function inRole($role, User $user = null){
        if($user===null){
            $user = \Auth::user();
        }
        return $this->roles()
            ->where('user_id', '=', $user->id)
            ->where('role', '=', $role)
            ->exists();
    }

    public static function scopeForRole(Builder $query, $role, User $user = null){
        if($user===null){
            $user = \Auth::user();
        }

        $subQuery = Role::select('item_id')
            ->where('item_type', (new self)->getMorphClass())
            ->where('user_id', '=', $user->id)
            ->where('role', '=', $role);

        return $query->whereIn('id', $subQuery);
    }

    public function userRoles(User $user = null){
        if($user===null){
            $user = \Auth::user();
        }
        return $this->roles()
            ->where('user_id', '=', $user->id)
            ->pluck('role');
    }

    public function updateRoles($data){
        /** @var Collection $roles */
        $roles = $this->roles;

        $idsToDelete = $roles->pluck('id')->toArray();

        foreach ($data as $dataItem){
            $dbRoles = $roles->filter(function($role) use ($dataItem){
                return $role->role == $dataItem['role'] && $role->user_id == $dataItem['user_id'];
            });
            if($dbRoles->count() > 0){
                if (($key = array_search($dbRoles->first()->id, $idsToDelete)) !== false) {
                    unset($idsToDelete[$key]);
                }
            } else {
                $this->roles()->create(['user_id' =>$dataItem['user_id'], 'role' => $dataItem['role']]);
            }
        }
        foreach ($idsToDelete as $id){
            Role::find($id)->delete();
        }
    }

}

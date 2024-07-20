<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\User;

class UserPolicy {


    public function before(User $user) {
        if ($user->admin && $user->admin->hasRole(Admin::ROLE_MANAGE_USERS)) {
            return true;
        }
    }

    public function viewAny(User $user): bool {
        return false;
    }

    public function create(User $user): bool {
        return false;
    }

    public function view(User $user, User $model): bool {
        return $model->id == $user->id;
    }

    public function update(User $user, User $model): bool {
        return $model->id == $user->id;
    }
}

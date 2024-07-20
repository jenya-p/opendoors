<?php

namespace App\Policies\Quiz;

use App\Models\Admin;
use App\Models\Quiz\Question;
use App\Models\Quiz\Quiz;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class QuizPolicy {

    public function before(User $user) {
        if ($user->admin && $user->admin->hasRole(Admin::ROLE_MANAGE_QUIZZES)) {
            return true;
        }
    }

    public function viewAny(User $user){
        return $user->hasAnyRoleOf(Quiz::class);
    }


    public function view(User $user, Quiz $quiz){
        return $user->hasAnyRoleOf($quiz);
    }

    public function create(User $user){
        return $user->hasAnyRoleOf(Quiz::class, [Quiz::ROLE_MANAGER]);
    }

    public function update(User $user, Quiz $quiz){
        return $user->hasAnyRoleOf($quiz, [Quiz::ROLE_EDITOR, Quiz::ROLE_MANAGER]);
    }

    public function delete(User $user, Quiz $quiz){
        return $user->hasAnyRoleOf($quiz, [Quiz::ROLE_MANAGER]);
    }

    public function restore(User $user, Quiz $quiz){
        return $user->hasAnyRoleOf($quiz, [Quiz::ROLE_MANAGER]);
    }

    public function forceDelete(User $user, Quiz $quiz){

    }
}

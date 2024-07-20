<?php

namespace App\Policies\Quiz;

use App\Models\Admin;
use App\Models\Attachment;
use App\Models\Quiz\Quiz;
use App\Models\Quiz\Question;
use App\Models\User;

class QuestionPolicy {

    public function before(User $user) {
        if ($user->admin && $user->admin->hasRole(Admin::ROLE_MANAGE_QUIZZES)) {
            return true;
        }
    }

    public function viewAny(User $user){
        return $user->hasAnyRoleOf(Quiz::class);
    }


    public function view(User $user, Question $question){
        return $user->hasAnyRoleOf($question);
    }

    public function create(User $user){
        return $user->hasAnyRoleOf(Quiz::class, [Quiz::ROLE_EDITOR, Quiz::ROLE_MANAGER]);
    }

    public function update(User $user, Question $question){
        return $user->hasAnyRoleOf($question->quiz, [Quiz::ROLE_EDITOR, Quiz::ROLE_MANAGER]);
    }

    public function delete(User $user, Question $question){
        return $user->hasAnyRoleOf($question->quiz, [Quiz::ROLE_EDITOR, Quiz::ROLE_MANAGER]);
    }

    public function restore(User $user, Question $question){

    }

    public function forceDelete(User $user, Question $question){

    }
}

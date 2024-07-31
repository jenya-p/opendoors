<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Quiz\Group;
use App\Models\Quiz\Question;
use App\Models\Quiz\Quiz;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Util\Json;
use Tests\TestCase;

class RolesTest extends TestCase {

    public function testRoles(): void {

        $user = User::find(2);

        $this->actingAs($user);

        if( \Auth::user()->can('viewAny', Quiz::class)){
            echo 'Доступно';
        };
    }


    public function testUserParticipants(){
        $user = \App\Models\User::find(278);

        dd($user->participant()->exists());

    }

}

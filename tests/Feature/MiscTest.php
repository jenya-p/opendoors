<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Quiz\Group;
use App\Models\Quiz\Question;
use App\Models\Quiz\Quiz;
use PHPUnit\Util\Json;
use Tests\TestCase;

class MiscTest extends TestCase {
    /**
     * A basic test example.
     */
    public function testQuiz(): void {


        echo 2;

        $q = Question::first();
        echo 2;
        $q->translate('en');

        echo 2;
        $q->refresh();
        echo 2;
        $q->update(['description' => 'dasdasd']);
        dd($q->toArray());


    }


}

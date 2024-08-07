<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Dir\KnowledgeArea;
use App\Models\Profile;
use App\Models\Quiz\Group;
use App\Models\Quiz\Question;
use App\Models\Quiz\Quiz;
use PHPUnit\Util\Json;
use Tests\TestCase;

class MiscTest extends TestCase {
    /**
     * A basic test example.
     */
    public function testKnowledgeArea(): void {

        dd(config('achievements'));

    }


}

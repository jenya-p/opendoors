<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Profile;
use App\Models\ProfileFileType;
use App\Models\Quiz\Group;
use App\Models\Quiz\Question;
use App\Models\Quiz\Quiz;
use App\Models\University;
use PHPUnit\Util\Json;
use Tests\TestCase;

class HasAttachmentsTest extends TestCase {

    public function testUniversity(): void {

        $univer = University::first();
        $univer->load('logo', 'logo_en')->translate();
        dd($univer->toArray());

    }

    public function testProfiles(){
        $profile = Profile::find(45);
        $materials = $profile->getFilesOfType('results');

        dd($materials->toArray());

    }

}

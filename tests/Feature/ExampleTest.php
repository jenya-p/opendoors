<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\ProfileFileType;
use App\Models\Track;
use Tests\TestCase;

class ExampleTest extends TestCase
{
   public function testProfileFileTypes2Tracks(){
       $t = Track::find(19);
       dd($t->profileFileTypes->toArray());

       $pft = ProfileFileType::find(3);
       dd($pft->tracks->toArray());
   }
}

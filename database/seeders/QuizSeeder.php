<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Attachment;
use App\Models\EduLevel;
use App\Models\Profile;
use App\Models\QuizGroup;
use App\Models\Stage;
use App\Models\Track;
use App\Models\University;
use App\Models\UniversityUser;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker\Factory;
use GuzzleHttp\Client;
use Heriw\LaravelSimpleHtmlDomParser\HtmlDomParser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class QuizSeeder extends Seeder {

    public function run(): void {
        QuizGroup::create([
            'name' => 'Алгебра',
        ]);

        QuizGroup::create([
            'name' => 'Геометрия',
        ]);

    }


}

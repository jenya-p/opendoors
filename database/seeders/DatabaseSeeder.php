<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Attachment;
use App\Models\EduLevel;
use App\Models\Profile;
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

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     */
    public function run(): void {
        // User::factory(10)->create();


        $this->trackAndStages();
        $this->universities();



    }


    public function eduLevels(){
        $elEn = [
            'pre-school',
            'primary general',
            'basic general',
            'general secondary',
            'secondary vocational',
            'higher: Bachelor\'s degree',
            'higher: specialty, Master\'s degree',
            'higher: training of highly qualified personnel'];

        $elRu = [
            'дошкольное',
            'начальное общее',
            'основное общее',
            'среднее общее',
            'среднее профессиональное',
            'высшее: бакалавриат',
            'высшее: специалитет, магистратура',
            'высшее: подготовка кадров высшей квалификации',
        ];

        foreach ($elRu as $index => $nameRu){
            EduLevel::firstOrCreate([
                'name' => $nameRu,
                'name_en' => $elEn[$index]
            ]);
        }

    }

    public function universities() {
        $client = new Client([
            'base_uri' => 'https://od.globaluni.ru',
        ]);


        $resp = $client->get('/?needRussian=1');

        $htmlRu = HtmlDomParser::str_get_html($resp->getBody());
        $resp = $client->get('/en/?needRussian=0');
        $htmlEn = HtmlDomParser::str_get_html($resp->getBody());
        $htmlNamesRu = $htmlRu->find('#universities .pt-1');
        $htmlImages = $htmlRu->find('#universities .universities-img');
        $htmA = $htmlRu->find('#universities a');
        $htmlNamesEn = $htmlEn->find('#universities .pt-1');

        foreach ($htmlNamesRu as $index => $item) {

            $tempImage = tempnam(sys_get_temp_dir(), uniqid());
            copy('https://od.globaluni.ru' . $htmlImages[$index]->src, $tempImage);

            $file = \Storage::putFile(Attachment::ITEM_TYPE_UNIVERSITY_LOGO, $tempImage);

            $university = University::create([
                'name' => $item->text(),
                'name_en' => $htmlNamesEn[$index]->text(),
                'url' => $htmA[$index]->href,
            ]);

            $attachment = Attachment::create([
                'item_type' => Attachment::ITEM_TYPE_UNIVERSITY_LOGO,
                'item_id' => $university->id,
                'file' => $file,
                'name' => basename($htmlImages[$index]->src)
            ]);
        }
    }

    public function trackAndStages(){
        $tracks = [];
        $tracks[] = Track::firstOrCreate([
            'name' => 'Бакалавриат', 'name_en' => 'Bachelor'
        ]);

        $tracks[] = Track::firstOrCreate([
            'name' => 'Магистратура', 'name_en' => 'Master'
        ]);

        $tracks[] = Track::firstOrCreate([
            'name' => 'Аспирантура', 'name_en' => 'Postgraduate'
        ]);

        $client = new Client([
            'base_uri' => 'https://od.globaluni.ru',
        ]);


        $profile = Profile::firstOrCreate([
            'name' => 'Арифметика',
            'name_en' => 'Arithmetic',
        ]);
        $this->stages($profile, $tracks);


        $resp = $client->get('/?needRussian=1');
        $htmlRu = HtmlDomParser::str_get_html($resp->getBody()->getContents());
        $resp = $client->get('/en/?needRussian=0');
        $htmlEn = HtmlDomParser::str_get_html($resp->getBody()->getContents());

        $htmlNamesRu = $htmlRu->find('.subjects-item-link .section-title');
        $htmlNamesEn = $htmlEn->find('.subjects-item-link .section-title');

        foreach ($htmlNamesRu as $index => $item) {
            $profile = Profile::firstOrCreate([
                'name' => trim($item->text()),
                'name_en' => trim($htmlNamesEn[$index]->text()),
            ]);
            $this->stages($profile, $tracks);

        }
    }


    public function stages($profile, $tracks){

        Stage::create([
            'track_id' => $tracks[0]->id,
            'profile_id' => $profile->id,
            'type' => 'portfolio',
        ]);

        Stage::create([
            'track_id' => $tracks[0]->id,
            'profile_id' => $profile->id,
            'type' => 'quiz',
        ]);

        Stage::create([
            'track_id' => $tracks[1]->id,
            'profile_id' => $profile->id,
            'type' => 'portfolio',
        ]);

        Stage::create([
            'track_id' => $tracks[1]->id,
            'profile_id' => $profile->id,
            'type' => 'quiz',
        ]);

        Stage::create([
            'track_id' => $tracks[2]->id,
            'profile_id' => $profile->id,
            'type' => 'portfolio',
        ]);

        Stage::create([
            'track_id' => $tracks[2]->id,
            'profile_id' => $profile->id,
            'type' => 'quiz',
        ]);

        Stage::create([
            'track_id' => $tracks[2]->id,
            'profile_id' => $profile->id,
            'type' => 'interview',
        ]);
    }

    public function testUsers(){
        $f = Factory::create();

        for ($i = 0; $i < 150; $i++) {
            $user = User::create([
                'name' => $f->name,
                'email' => $f->email,
                'password' => Hash::make('123456')
            ]);
        }
die;
//        $user->admin()->create([
//            'roles' => array_keys(Admin::ROLES)
//        ]);


//       $user = User::factory()->create([
//            'name' => 'Admin 2',
//            'email' => 'admin@opendoors.ru',
//            'password' => Hash::make('123456')
//        ]);

//        $user->admin()->create([
//            'roles' => array_keys(Admin::ROLES)
//        ]);

        $uIds = University::pluck('id')->toArray();

        /** @var User $user */
        $user = User::factory()->create([
            'name' => 'Менеджер Вуза 1',
            'email' => 'uni1@opendoors.ru',
            'password' => Hash::make('123456')
        ]);


        $user->universityUsers()->create([
            'university_id' => $uIds[0],
            'roles' => [UniversityUser::ROLE_MANAGE_SITE, UniversityUser::ROLE_MANAGE_USERS]
        ]);

        $user->universityUsers()->create([
            'university_id' => $uIds[1],
            'roles' => [UniversityUser::ROLE_MANAGE_SITE, UniversityUser::ROLE_MANAGE_USERS, UniversityUser::ROLE_MANAGE_INTERVIEW]
        ]);


        /** @var User $user */
        $user = User::factory()->create([
            'name' => 'Менеджер Вуза 2',
            'email' => 'uni2@opendoors.ru',
            'password' => Hash::make('123456')
        ]);


        $user->universityUsers()->create([
            'university_id' => $uIds[0],
            'roles' => [UniversityUser::ROLE_MANAGE_INTERVIEW, UniversityUser::ROLE_INTERVIEWER]
        ]);

        $user->universityUsers()->create([
            'university_id' => $uIds[1],
            'roles' => [UniversityUser::ROLE_INTERVIEWER]
        ]);

        $user->universityUsers()->create([
            'university_id' => $uIds[3],
            'roles' => [UniversityUser::ROLE_INTERVIEWER]
        ]);


    }

}

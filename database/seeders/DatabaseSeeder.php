<?php

namespace Database\Seeders;

use App\Models\Attachment;
use App\Models\Content\Faq;
use App\Models\Content\FaqCategory;
use App\Models\Content\News;
use App\Models\Content\Schedule;
use App\Models\Content\Widget;
use App\Models\Dir\Citizenship;
use App\Models\Dir\Country;
use App\Models\Dir\KnowledgeArea;
use App\Models\Dir\Region;
use App\Models\EduLevel;
use App\Models\Profile;
use App\Models\ProfileFile;
use App\Models\ProfileFileType;
use App\Models\Stage;
use App\Models\StudyProgram;
use App\Models\Track;
use App\Models\University;
use App\Models\UniversityProfile;
use App\Models\UniversityUser;
use App\Models\User;
use Faker\Factory;
use GuzzleHttp\Client;
use Heriw\LaravelSimpleHtmlDomParser\HtmlDomParser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use simplehtmldom\simple_html_dom_node;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     */
    public function run(): void {

        $data = $this->areas();

        // $this->countries();
//        $this->methhodists();
//        $this->partners();
//
//        $this->profileFileTypes();
//
//        $this->profileFiles();

    }


    public function areas(){

        \DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        DB::table('dir_knowledge_areas')->truncate();
        DB::table('profile_areas')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1;');

        $profileIdsMap = DB::table('import_subject_area')->pluck('od_id', 'ID')->toArray();

        $ids = [];
        $data = $this->exl('knowledge_area_block');
        foreach ($data as $datum){
            $ids[$datum['ID']] = KnowledgeArea::create($datum)->id;
        }
        $ids2 = [];
        $data = $this->exl('knowledge_area');
        foreach ($data as $datum){
            $ids2[$datum['ID']] =
                KnowledgeArea::create(\Arr::only($datum, ['name', 'name_en', 'code']) + [
                    'parent_id' => $ids[$datum['block_id']]
                ])->id;
        }

        $data = $this->exl('knowledge_directions');
        foreach ($data as $datum){
            $area = KnowledgeArea::create(\Arr::only($datum, ['name', 'name_en', 'code']) + [
                        'parent_id' => $ids2[$datum['area_id']]
                    ]);

            if(!empty($datum['selected_profiles'])){
                $profileIds = json_decode($datum['selected_profiles']);
                $profileIds = array_map(fn($id) => $profileIdsMap[$id], $profileIds);
                $area->profiles()->sync($profileIds);
            }
        }

    }

    public function countries(){

        \DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        DB::table('dir_countries')->truncate();
        DB::table('dir_regions')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1;');

        // $countries = $this->exl('country');


        $regionMap = [];
        $regions = $this->exl('region');
        foreach ($regions as $reg){
            $regionMap[$reg['ID']] = Region::create([
                'name' => $reg['name'],
                'name_en' => $reg['name_en'],
                'code' => $reg['code'],
            ])->id;
        }
        $countries = $this->exl('country');
        foreach ($countries as $country){
            Country::create([
                'name' => $country['name'],
                'name_en' => $country['name_en'],
                'code' => $country['iso_code'],
                'region_id' => array_key_exists($country['region_id'], $regionMap) ? $regionMap[$country['region_id']] : null
            ]);
        }
    }


     public function exl($name){

        $excel = IOFactory::load(resource_path('temp/' . $name . '.xls'));
        $sheet = $excel->getActiveSheet();
        $keys = [];

        $i = 1;

        for($j = 1; !empty($value = $sheet->getCell([$j, $i])->getValue()); $j++){
            $keys[$j] = $value;
        }

        $data = [];
        for($i = 2; $i <= $sheet->getHighestRow(); $i++){
            $item = [];
            foreach ($keys as $j => $key){
                $item[$key] = $sheet->getCell([$j, $i])->getValue();
            }
            $data[] = $item;
        }

        return $data;

    }

    public function citizenship(){

        \DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        DB::table('dir_citizenships')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1;');

        $f = fopen(resource_path('temp/Citizenships.csv'), 'r');
        $titleCase = function ($string, $delimiters = array(" ", "-", ".", "(", ")"), $exceptions = array("и", "to", "д", "of", "the", "and", 'part', 'часть')){

            $string = mb_convert_case($string, MB_CASE_TITLE, "UTF-8");
            foreach ($delimiters as $dlnr => $delimiter) {
                $words = explode($delimiter, $string);
                $newwords = array();
                foreach ($words as $wordnr => $word) {
                    if (in_array(mb_strtoupper($word, "UTF-8"), $exceptions)) {
                        // check exceptions list for any words that should be in upper case
                        $word = mb_strtoupper($word, "UTF-8");
                    } elseif (in_array(mb_strtolower($word, "UTF-8"), $exceptions)) {
                        // check exceptions list for any words that should be in upper case
                        $word = mb_strtolower($word, "UTF-8");
                    } elseif (!in_array($word, $exceptions)) {
                        // convert to uppercase (non-utf8 only)
                        $word = ucfirst($word);
                    }
                    array_push($newwords, $word);
                }
                $string = join($delimiter, $newwords);
            }//foreach
            return $string;
        };

        while (false !== $row = fgetcsv($f, null, ';')){
            echo 1;
            if(!count($row) > 2){
                dd($row);
            } else {
                Citizenship::create([
                    'name' => $titleCase($row[0]),
                    'name_en' => $titleCase($row[1]),
                    'code' => $row[2],
                ]);
            }

        }

    }


    public function methhodists(){

        $one = function($name, $email, $password){
            User::updateOrCreate([
                'email' => trim($email),
            ], [
                'name' => trim($name),
                'password' => Hash::make($password)
            ]);
        };

        $one('Виноградова Анна Владимировна', 'avv21@yandex.ru', '63L9gQbzfPTq', 'FALSE', '+79023038551');
        $one('Гигани Ольга Олеговна', 'gigani-oo@rudn.ru', 'Sc1gJDs6g7dS', 'FALSE', '+79161188786');
        $one('Гриневич Юлия Анатольевна', 'julia-grinevich@mail.ru', 'GXpNxftiFR8C', 'FALSE', '+79202910032');
        $one('Деева Анна Андреевна', 'ADeeva@sfu-kras.ru', 'Fvwnzu0F23Z4', 'FALSE', '+79082152010');
        $one('Демченко Екатерина Александровна', 'e.a.demchenko@urfu.ru', 'y9DiNmJSZL8a', 'FALSE', '');
        $one('Денисов Вадим Вадимович', 'vadim.denisov@itmm.unn.ru', 'zdQZPlRBDBhf', 'FALSE', '+79601715300');
        $one('Дьячкова Анастасия Викторовна', 'av.diachkova@urfu.ru', 'aInOMM03Zhx6', 'FALSE', '');
        $one('Жаринова Вероника Владимировна', 'nika_zharinova@mail.ru', 'A0JhhmVb1o1U', 'FALSE', '');
        $one('Зарянский Сергей Александрович', 's.a.zariansky@urfu.ru ', 'esQcHMJD3R9T', 'FALSE', '');
        $one('Зарянский Сергей Александрович', 's.a.zariansky@urfu.ru ', 'esQcHMJD3R9T', 'FALSE', '');
        $one('Зарянский Сергей Александрович', 's.a.zariansky@urfu.ru ', 'esQcHMJD3R9T', 'FALSE', '');
        $one('Калитин Денис Владимирович', 'kalitindv@misis.ru', '34Njo8JLf88l', 'FALSE', '');
        $one('Капралова Дарья Олеговна', 'kapralova-do@pfur.ru', 'TfJZyB9Ssbvc', 'FALSE', '+79104708495');
        $one('Карпов Олег Николаевич', 'onkarpov@etu.ru', 'TMqiUBE6KsBN', 'FALSE', '+79817496425');
        $one('Кемаев Константин Валерьевич', 'kvk@unn.ru', 'w4AtsnjEdFft', 'FALSE', '+79030432526');
        $one('Кемаев Константин Валерьевич', 'kvk@unn.ru', 'w4AtsnjEdFft', 'FALSE', '+79030432526');
        $one('Кемаев Константин Валерьевич', 'kvk@unn.ru', 'w4AtsnjEdFft', 'FALSE', '+79030432526');
        $one('Кемаева Марина Владимировна', 'mvkemaeva@ya.ru', 'u2QUONyPreK1', 'FALSE', '+79200217141');
        $one('Киселева Марина Владимировна ', 'kiseleva.mv@dvfu.ru', '9qNmNiJXu76n', 'FALSE', '+79024816929');
        $one('Киселева Марина Владимировна ', 'kiseleva.mv@dvfu.ru', '9qNmNiJXu76n', 'FALSE', '+79024816929');
        $one('Козарь Марина Валерьевна', 'kozar_m_v@staff.sechenov.ru', '38MrVbQT2KnB', 'FALSE', '');
        $one('Кондаков Александр Викторович', 'kav-03@mail.ru', 'H68gJwBQ2KyS', 'FALSE', '+79500100391');
        $one('Коршунова Анна Анатольевна', 'tolstova_301@mail.ru', '5Cw3PyMCQ855', 'FALSE', '+79138450978');
        $one('Коршунова Анна Анатольевна', 'tolstova_301@mail.ru', '5Cw3PyMCQ855', 'FALSE', '+79138450978');
        $one('Куранов Георгий Леонидович', 'glkuranov@etu.ru', 'nM66wJ5aanoO', 'FALSE', '+79119201277');
        $one('Ли Элина Валерьевна', 'li_elina2787@mail.ru', '9rCERtiGpXys', 'TRUE', '');
        $one('Ли Элина Валерьевна', 'li_elina2787@mail.ru', '9rCERtiGpXys', 'TRUE', '');
        $one('Ли Элина Валерьевна', 'li_elina2787@mail.ru', '9rCERtiGpXys', 'TRUE', '');
        $one('Мальцева Ирина Николаевна', 'i.n.maltceva@urfu.ru', 'usL5QwCQ9sty', 'FALSE', '');
        $one('Мартина Екатерина Владимировна ', 'kvk@unn.ru', 'kK47vdIsicJc', 'FALSE', '+79030432526');
        $one('Мартина Екатерина Владимировна ', 'kvk@unn.ru', 'kK47vdIsicJc', 'FALSE', '+79030432526');
        $one('Микерова Мария Сергеевна', 'mikerova_m_s@staff.sechenov.ru', 'UIa2LUfcVBby', 'FALSE', '');
        $one('Морозова Виктория Владимировна', 'morozova_v_v@staff.sechenov.ru', 'w85tX5O3wNOV', 'FALSE', '');
        $one('Морозова Виктория Владимировна', 'morozova_v_v@staff.sechenov.ru', 'w85tX5O3wNOV', 'FALSE', '');
        $one('Наземцева Мария Андреевна', 'mnazemtseva@gmail.com', '861AXBic9xcz', 'FALSE', '+79833448955');
        $one('Носкова Елена Викторовна', 'noskova.ev@dvfu.ru', 'UttChaaPtDuA', 'FALSE', '+79025561446');
        $one('Подгорная Людмила Николаевна ', 'lnpodgornaya@etu.ru', 'oYfD743tdDw0', 'FALSE', '+79043352207');
        $one('Подгорная Людмила Николаевна ', 'lnpodgornaya@etu.ru', 'oYfD743tdDw0', 'FALSE', '+79043352207');
        $one('Подгорная Людмила Николаевна ', 'lnpodgornaya@etu.ru', 'oYfD743tdDw0', 'FALSE', '+79043352207');
        $one('Подгорный Дмитрий Андреевич', 'podgorny_d@misis.ru', 'vDwuz9v42sv7', 'FALSE', '');
        $one('Попкова Анна Владимировна', 'popkova_av@pfur.ru', 'bw26AWPRjGtU', 'FALSE', '+79689460929');
        $one('Родин Алексей Олегович', 'rodin@misis.ru', 'CcbDo0KljM5B', 'FALSE', '');
        $one('Савельева Нэлли Хисматуллаевна', 'n.kh.savelyeva@urfu.ru ', 'CegnBmKc7Wtv', 'FALSE', '');
        $one('Самарченко Дмитрий Александрович', 'Dasamarchenko@mephi.ru', '36vLTLuC8QZ5', 'FALSE', '');
        $one('Саталкина Евгения Васильевна', 'satalkina@spbstu.ru', '2R4Oq96Yx3wN', 'FALSE', '');
        $one('Саталкина Евгения Васильевна', 'satalkina@spbstu.ru', '2R4Oq96Yx3wN', 'FALSE', '');
        $one('Седых Сергей Евгеньевич', 'sirozha@gmail.com', 'bfC739x8IWgs', 'FALSE', '+79137271000');
        $one('Смирнова Елена Александровна', 's_elena_84@mail.ru', '6H320rCuG3kr', 'FALSE', '');
        $one('Соловьев Владимир Игоревич', 'root08vlad@gmail.com', 'DvlSeqNPPb5K', 'FALSE', '+79231428561');
        $one('Степанова Анастасия Андреевна', 'astepanova@sfu-kras.ru', 'UxgBCE5zcWqP', 'FALSE', '+79233025065');
        $one('Степанова Анастасия Андреевна', 'astepanova@sfu-kras.ru', 'UxgBCE5zcWqP', 'FALSE', '+79233025065');
        $one('Тимофеев Александр Викторович', 'avtimofeev@etu.ru', 'Fvbid41Jic49', 'FALSE', '+79119124323');
        $one('Титова Елена Борисовна', 'elena.titova@itmm.unn.ru', 'tPPaHRebMHOy', 'FALSE', '+79200295872');
        $one('Тюфлин Сергей Александрович', 'opendoors@mephi.ru', 'kaRSGmsKX0At', 'FALSE', '');
        $one('Тюфлин Сергей Александрович', 'opendoors@mephi.ru', 'kaRSGmsKX0At', 'FALSE', '');
        $one('Унагаева Наталья Александровна', 'nunagaeva@sfu-kras.ru ', '7GUp9Zaf9CCk', 'FALSE', '+79607692738');
        $one('Федченко Ирина Геннадьевна', 'ifedchenko@sfu-kras.ru', 'knVfvfsfLyIx', 'FALSE', '+79233056009');
        $one('Филиппова Ирина Панфиловна', 'phix@mail.ru', '39jHZcfp7i0P', 'FALSE', '+79135690285');
        $one('Шурухина Татьяна Николаевна', 'shuruhina.tn@dvfu.ru', 'jQzUQ1HWWHR9', 'FALSE', '+79147103135');
        $one('Эйлер Анна Владимировна', 'eiler.av@dvfu.ru', '2bAGdPZLC9uj', 'FALSE', '+79146686717');
        $one('Эрдынеева Клавдия Гомбожаповна', 'erdyneeva.kg@dvfu.ru', 'ba9iO3xPMTmB', 'FALSE', '+79243829450');
        $one('Ястребова Ирина Юрьевна ', 'irina_yastrebova@rambler.ru', 'UdUP5pLGOX6r', 'FALSE', '+79026851813');
    }



    public function profileFiles() {
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        DB::table('profile_files')->truncate();
        Attachment::where('item_type', 'profile_file')->delete();
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1;');


        $client = new Client([
            'base_uri'=> 'https://od.globaluni.ru',
        ]);


        $resp = $client->get('/subject/business');
        $htmlRu = HtmlDomParser::str_get_html($resp->getBody());
        $resp = $client->get('/en/subject/business');
        $htmlEn = HtmlDomParser::str_get_html($resp->getBody());

        $htmlProfilesRu = $htmlRu->find('.subjects-item a');
        $htmlProfilesEn = $htmlEn->find('.subjects-item a');

        $doProfile = function (Profile $profile, $url, $locale) use ($client) {
            $resp = $client->get($url);
            $html = HtmlDomParser::str_get_html($resp->getBody());

            $htmlItems = $html->find('.materials-item');

            foreach ($htmlItems as $htmlItem) {
                $title = $htmlItem->find('.materials-name',0)->text();


                $fileType = ProfileFileType::where([($locale == 'en'? 'name_en': 'name') => $title])->firstOrFail();

                $profileFile = ProfileFile::firstOrCreate([
                    'type_id'=> $fileType->id,
                    'profile_id'=> $profile->id
                ], ['status'=> 'active']);

                $pdfUrl = 'https://od.globaluni.ru'. $htmlItem->find('.materials-download', 0)->href;
                $tempImage = tempnam(sys_get_temp_dir(), uniqid());
                copy($pdfUrl, $tempImage);
                $file = \Storage::putFile(Attachment::ITEM_TYPE_PROFILE_FILE, $tempImage);

                $attachmentRu = Attachment::create([
                    'item_type'=> Attachment::ITEM_TYPE_PROFILE_FILE,
                    'item_id'=> $profileFile->id,
                    'file'=> $file,
                    'type'=> $locale,
                    'name'=> basename($profileFile->id . '-'. $locale . '.'. pathinfo($pdfUrl, PATHINFO_EXTENSION))
                ]);
            }
        };

        /** @var simple_html_dom_node $item */
        foreach ($htmlProfilesRu as $index => $item) {

            $titleRu = $item->find('img', 0)->alt;
            $titleEn = $htmlProfilesEn[$index]->find('img', 0)->alt;
            $urlRu = $item->href;
            $urlEn = $htmlProfilesEn[$index]->href;

            $profile = Profile::
                where('name', '=', $titleRu)
                ->where('name_en', '=', $titleEn)
                ->first();

            $doProfile($profile, $urlRu, 'ru');
            $doProfile($profile, $urlEn, 'en');

        }



    }

    public function profileFileTypes() {
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        DB::table('profile_file_types')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1;');


        ProfileFileType::create([
            'type'=> 'materials',
            'name_en'=> 'Demo version',
            'summary_en'=> '<p>Training tasks similar to those, you will face with at the second round of the competition.</p>',
            'name'=> 'Демо версия заданий',
            'summary'=> '<p>Демонстрационный вариант заданий второго этапа.</p>',
        ]);

        ProfileFileType::create([
            'type'=> 'materials',
            'name'=> 'Программа',
            'summary'=> '<p>Программа профиля</p>',
            'name_en'=> 'Program of Subject Area',
            'summary_en'=> '<p>Program outline.</p>',
        ]);

        ProfileFileType::create([
            'track_id'=> 19,
            'type'=> 'materials',
            'name_en'=> 'Interview outline',
            'summary_en'=> '<p>For doctoral track’s participants only</p>',
            'name'=> 'Программа собеседования',
            'summary'=> '<p>Только для участников трека аспирантуры</p>',
        ]);

        ProfileFileType::create([
            'track_id'=> 19,
            'type'=> 'materials',
            'name_en'=> 'List of potential scientific supervisors',
            'summary_en'=> '<p>For doctoral track’s participants only</p>',
            'name'=> 'Перечень научных руководителей',
            'summary'=> '<p>Только для участников трека аспирантуры</p>',
        ]);

        ProfileFileType::create([
            'name'=>'Участники 2 этапа',
            'name_en'=>'Participants of the 2nd stage',
            'track_id'=> null,
            'type'=> 'results',
        ]);

        ProfileFileType::create([
            'name'=> 'Победители и призеры по треку магистратуры',
            'name_en'=>'Winners and prize-winners of the master\'s track',
            'track_id'=> null,
            'type'=> 'results',
        ]);

        ProfileFileType::create([
            'name'=>'Участники 3 этапа',
            'name_en'=>'Participants of the 3rd stage of the doctoral track',
            'track_id'=> 19,
            'type'=> 'results',
        ]);

        ProfileFileType::create([
           'name'=> 'Победители по треку аспирантуры',
           'name_en'=> 'Winners of the doctoral track',
            'track_id'=> 19,
            'type'=> 'results',
        ]);


    }

    public function pages() {


        Widget::updateOrCreate(['key'=> 'home.top'], [
            //'schema'=> 'home.top',
            'data'=> [
                'h1'=> 'One [click] to
open all doors',
                'description_en'=> 'Open Doors: Russian Scholarship Project is your chance for early admission to a tuition-free program at one of the leading universities in Russia.',
                'description'=> 'Международная олимпиада Ассоциации «Глобальные университеты» для абитуриентов магистратуры и аспирантуры — это Ваш ключ к досрочному поступлению и бесплатному обучению в России.',
            ]
        ]);

        Widget::updateOrCreate(['key'=> 'footer'], [
            //'schema'=> 'home.top',
            'data'=> [
                'address_en'=> 'Moscow, 11 Pokrovsky Bulvar, Building 10, D-714',
                'address'=> 'г. Москва, Покровский б-р, д.11, стр. 10',
                'email'=> 'opendoors@globaluni.ru',
                'vk'=> null,
                'telegram'=> null,
            ]
        ]);

        Widget::updateOrCreate(
            ['key'=> 'about'], [
                'data'=> [
                    'h1'=> 'Об Олимпиаде',
                    'content_en'=> file_get_contents(resource_path('temp/scratch_1.html')),
                    'content'=> file_get_contents(resource_path('temp/scratch_2.html')),
                    'webinars'=> [[
                        'youtube'=> 'Sbeyr4CoZ28',
                        'title'=> 'Вебинар с представителями СПбГЭУ "ЛЭТИ" и ИТМО',
                        'title_en'=> 'Webinar with the representatives of LETI and ITMO universities',
                    ], [
                        'youtube'=> 'IGeyH7nKPzk',
                        'title'=> 'Вебинар с представителями ННГУ и КФУ',
                        'title_en'=> 'Webinar with the representatives of the UNN and KFU',
                    ], [
                        'youtube'=> 'IggMSPEIHFU',
                        'title'=> 'Вебинар с представителями ДВФУ и ТюмГУ',
                        'title_en'=> 'Webinar with the representatives of the FEFU and UTMN',
                    ]
                    ]

                ]
            ]
        );


        $widget = Widget::firstOrCreate(['key'=> 'rules'], ['data'=> []]);


        $file = \Storage::putFile('widgets', resource_path('temp/v7s0qj60zdj27fe34g3use27fnbimhwk.pdf'));
        $attachmentRu = Attachment::create([
            'item_type'=> Attachment::ITEM_TYPE_WIDGET,
            'item_id'=> 0,
            'file'=> $file,
            'name'=> basename('rules-ru.pdf')
        ]);

        $file = \Storage::putFile('widgets', resource_path('temp/v7s0qj60zdj27fe34g3use27fnbimhwk.pdf'));
        $attachmentEn = Attachment::create([
            'item_type'=> Attachment::ITEM_TYPE_WIDGET,
            'item_id'=> 0,
            'file'=> $file,
            'name'=> basename('rules-en.pdf')
        ]);


        $widget->update(

            ['data'=> [
                'title'=> 'Правила проведения',
                'title_en'=> 'Rules',
                'blocks'=> [[
                    'title_en'=> 'Demo version',
                    'summary_en'=> 'Training tasks similar to those, you will face with at the second round of the competition.',
                    'title'=> 'Демонстрационная версия',
                    'summary'=> 'Тренировочные задания, аналогичные тем, с которыми вы столкнетесь во втором туре конкурса.',
                    'file_id'=> $attachmentRu->id,
                    'file_id_en'=> $attachmentEn->id
                ], [
                    'title_en'=> 'Interview outline',
                    'summary_en'=> 'For doctoral track’s participants only',
                    'title'=> 'План собеседования',
                    'summary'=> 'Только для участников докторской программы',
                    'file_id'=> $attachmentRu->id,
                    'file_id_en'=> $attachmentEn->id
                ], [

                    'title_en'=> 'List of potential scientific supervisors',
                    'summary_en'=> 'For doctoral track’s participants only',
                    'title'=> 'Список потенциальных научных руководителей',
                    'summary'=> 'Только для участников докторской программы',
                    'file_id'=> $attachmentRu->id,
                    'file_id_en'=> $attachmentEn->id
                ]
                ]
            ]]);


        Widget::updateOrCreate(
            ['key'=> 'tracks'], [
                'data'=> [
                    'h1_en'=> 'Olympiad tracks',
                    'h1'=> 'Треки Олимпиады',
                    'content_en'=> file_get_contents(resource_path('temp/scratch_3.html')),
                    'content'=> file_get_contents(resource_path('temp/scratch_4.html')),
                ]
            ]
        );


    }

    public function partners() {


        $client = new Client([
            'base_uri'=> 'https://od.globaluni.ru',
        ]);

        $resp = $client->get('/');
        $htmlRu = HtmlDomParser::str_get_html($resp->getBody());
        $resp = $client->get('/en/');
        $htmlEn = HtmlDomParser::str_get_html($resp->getBody());

        $htmlItemsRu = $htmlRu->find('#partners .row .col-6');
        $htmlItemsEn = $htmlEn->find('#partners .row .col-6');

        $widget = Widget::firstOrCreate(['key'=> 'home.partners'], [
            'data'=> []
        ]);

        $data = [];

        /** @var simple_html_dom_node $item */
        foreach ($htmlItemsRu as $index => $item) {

            $titleRu = $item->find('img', 0)->alt;
            $urlRu = $item->find('a', 0)->href;
            $imageRu = 'https://od.globaluni.ru'. $item->find('img', 0)->src;

            $titleEn = $htmlItemsEn[$index]->find('img', 0)->alt;
            $urlEn = $htmlItemsEn[$index]->find('a', 0)->href;
            $imageEn = 'https://od.globaluni.ru'. $htmlItemsEn[$index]->find('img', 0)->src;

            $tempImage = tempnam(sys_get_temp_dir(), uniqid());
            copy($imageRu, $tempImage);
            $file = \Storage::putFile('partners', $tempImage);

            $attachmentRu = Attachment::create([
                'item_type'=> Attachment::ITEM_TYPE_WIDGET,
                'item_id'=> 0,
                'file'=> $file,
                'name'=> basename($index . '-ru.'. pathinfo($imageRu, PATHINFO_EXTENSION))
            ]);

            $tempImage = tempnam(sys_get_temp_dir(), uniqid());
            copy($imageEn, $tempImage);
            $file = \Storage::putFile('partners', $tempImage);

            $attachmentEn = Attachment::create([
                'item_type'=> Attachment::ITEM_TYPE_WIDGET,
                'item_id'=> 0,
                'file'=> $file,
                'name'=> basename($index . '-en.'. pathinfo($imageRu, PATHINFO_EXTENSION))
            ]);

            $data[] = [
                'title'=> $titleRu,
                'title_en'=> $titleEn,
                'url'=> $urlRu,
                'url_en'=> $urlEn,
                'image_id'=> $attachmentRu->id,
                'image_id_en'=> $attachmentEn->id,
            ];

        }
        $widget->update([
            'data'=> $data
        ]);

    }


    public function profiles() {
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        DB::table('study_programs')->truncate();
        DB::table('university_profiles')->truncate();
        // DB::table('profile_files')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1;');


        $client = new Client([
            'base_uri'=> 'https://od.globaluni.ru',
        ]);


        $resp = $client->get('/subject/business');
        $htmlRu = HtmlDomParser::str_get_html($resp->getBody());
        $resp = $client->get('/en/subject/business');
        $htmlEn = HtmlDomParser::str_get_html($resp->getBody());

        $htmlProfilesRu = $htmlRu->find('.subjects-item a');
        $htmlProfilesEn = $htmlEn->find('.subjects-item a');


        $titlesRu = [];
        $titlesEn = [];

        $data = [];

        $doProfile = function (Profile $profile, $url, $locale) use ($client, $data) {
            $resp = $client->get($url);
            $html = HtmlDomParser::str_get_html($resp->getBody());

            $htmlItems = $html->find('.checklist-item');
            foreach ($htmlItems as $htmlItem) {
                $studyProgram = StudyProgram::firstOrCreate(['name'=> $htmlItem->find('div.pl-3>.pl-3', 0)->text(), 'locale'=> $locale]);

                $htmlUnis = $htmlItem->find('ul li');
                foreach ($htmlUnis as $htmlUni) {
                    $uniName = $htmlUni->find('div.pt-1', 0)->text();

                    $uni = University::where(
                        'name'. ($locale == 'en'? '_en': ''), $htmlUni->find('div.pt-1', 0)->text()
                    )->first();

                    if ($uni == null) {
                        echo 1;
                        $data[] = $uniName;
                    } else {
                        UniversityProfile::create([
                            'profile_id'=> $profile->id,
                            'university_id'=> $uni->id,
                            'program_id'=> $studyProgram->id
                        ]);
                    }
                }
            }

            return $html->find('.section-subtitle', 0)->text();

        };

        /** @var simple_html_dom_node $item */
        foreach ($htmlProfilesRu as $index => $item) {

            $title = $item->find('img', 0)->alt;
            $urlRu = $item->href;
            $titleEn = $htmlProfilesEn[$index]->find('img', 0)->alt;
            $urlEn = $htmlProfilesEn[$index]->href;

            $profile = Profile::
            where('name', '=', $title)
                ->where('name_en', '=', $titleEn)
                ->first();


//
            $coordinatorRu = $doProfile($profile, $urlRu, 'ru');
            $coordinatorEn = $doProfile($profile, $urlEn, 'en');


            if (!empty($coordinatorRu)) {
                $coordinatorRu = mb_substr($coordinatorRu, 49);
                $coordinatorEn = mb_substr($coordinatorEn, 37);
                $uni = University::firstOrCreate(['name'=> $coordinatorRu], ['name_en'=> $coordinatorEn]);
                $profile->update(
                    ['coordinator_id'=> $uni->id]
                );
            }


        }

        $icons = [
            "Арифметика TEST" => "arithmetic.svg",
            "Биология и биотехнологии" => "_2.svg",
            "Компьютерные науки и науки о данных" => "_4.svg",
            "Прикладная математика и искусственный интеллект" => "_6.svg",
            "Бизнес и менеджмент" => "_1.svg",
            "Политические науки и международные отношения" => "_3.svg",
            "Физико-технические науки" => "_5.svg",
            "Лингвистика и современные языки" => "_7.svg",
            "Химия и науки о материалах" => "_8.svg",
            "Экономика и эконометрика" => "_11.svg",
            "Инженерия и технологии" => "_13.svg",
            "Клиническая медицина и общественное здравоохранение" => "_10.svg",
            "Науки о Земле и окружающей среде" => "_12.svg",
            "Образование и психология" => "_9.svg",
            "Урбанистика и гражданское строительство" => "_14.svg",
        ];


        foreach ($icons as $name => $icon) {
            Profile::where(['name'=> $name])->update(['icon'=> $icon]);
        }


    }


    public function schedule() {
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        DB::table('schedule_items')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1;');


        $client = new Client([
            'base_uri'=> 'https://od.globaluni.ru',
        ]);

        $resp = $client->get('/');
        $htmlRu = HtmlDomParser::str_get_html($resp->getBody());
        $resp = $client->get('/en/');
        $htmlEn = HtmlDomParser::str_get_html($resp->getBody());

        $htmlNewsRu = $htmlRu->find('#schedule .row.no-gutters');
        $htmlNewsEn = $htmlEn->find('#schedule .row.no-gutters');
        $dt = 0;
        /** @var simple_html_dom_node $item */
        foreach ($htmlNewsRu as $index => $item) {

            $title = $item->find('.section-title-md div', 0)->text();
            $summary = $item->find('.section-common-text', 0)->text();

            $titleEn = $htmlNewsEn[$index]->find('.section-title-md div', 0)->innertext();
            $summaryEn = $htmlNewsEn[$index]->find('.section-common-text', 0)->innertext();

            $d = strtotime($htmlNewsEn[$index]->find('.time', 0)->text());

            if (empty($d)) {
                $dt += 24 * 60 * 60;
                $date = date('Y-m-d', $dt);
                $dt += 24 * 60 * 60;
                $dateTo = date('Y-m-d', $dt);
            } else {
                $dt = $d;
                $date = date('Y-m-d', $dt);
                $dateTo = date('Y-m-d', $dt);
            }


            Schedule::create([
                'date_from'=> $date,
                'date_to'=> $dateTo,
                'title'=> $title,
                'title_en'=> $titleEn,
                'summary'=> $summary,
                'summary_en'=> $summaryEn,
            ]);


        }
    }

    public function faq() {


        \DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        DB::table('faqs')->truncate();
        DB::table('faq_categories')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1;');

        $cats = [[
            'name'=> 'Общие вопросы',
            'name_en'=> 'General questions'
        ],
            [
                'name'=> 'Порфтолио',
                'name_en'=> 'Portfolio'
            ],
            [
                'name'=> 'Документы',
                'name_en'=> 'Documents'
            ],
            [
                'name'=> 'Выбор университета',
                'name_en'=> 'Choosing a University'
            ],
            [
                'name'=> 'Стипендия',
                'name_en'=> 'Scholarship'
            ]];

        foreach ($cats as $cat) {
            FaqCategory::create($cat);
        }

        $catIds = FaqCategory::pluck('id')->toArray();

        $client = new Client([
            'base_uri'=> 'https://od.globaluni.ru',
        ]);

        $resp = $client->get('/');
        $htmlRu = HtmlDomParser::str_get_html($resp->getBody());
        $resp = $client->get('/en/');
        $htmlEn = HtmlDomParser::str_get_html($resp->getBody());


        $htmlNewsRu = $htmlRu->find('.section-faq-content .faq-item');
        $htmlNewsEn = $htmlEn->find('.section-faq-content .faq-item');


        /** @var simple_html_dom_node $item */
        foreach ($htmlNewsRu as $index => $item) {

            $question = $item->find('.section-title-md', 0)->innertext();
            $answer = $item->find('.section-common-text', 0)->innertext();

            $questionEn = $htmlNewsEn[$index]->find('.section-title-md', 0)->innertext();
            $answerEn = $htmlNewsEn[$index]->find('.section-common-text', 0)->innertext();

            Faq::create([
                'category_id'=> \Arr::random($catIds),
                'question'=> trim($question),
                'question_en'=> trim($questionEn),
                'answer'=> trim($answer),
                'answer_en'=> trim($answerEn),
            ]);


        }
    }

    public function news() {
        $client = new Client([
            'base_uri'=> 'https://od.globaluni.ru',
        ]);

        DB::table('content_news')->truncate();


        $resp = $client->get('/news');
        $htmlRu = HtmlDomParser::str_get_html($resp->getBody());
        $resp = $client->get('/en/news/');
        $htmlEn = HtmlDomParser::str_get_html($resp->getBody());


        $htmlNewsRu = $htmlRu->find('.news-item-row');
        $htmlNewsEn = $htmlEn->find('.news-item-row');

        /** @var simple_html_dom_node $item */
        foreach ($htmlNewsRu as $index => $item) {

            $hrefRu = $item->find('a.news-title', 0)->href;
            $summary = $item->find('.news-item-text', 0)->innertext();
            $hrefEn = $htmlNewsEn[$index]->find('a.news-title', 0)->href;
            $summaryEn = $htmlNewsEn[$index]->find('.news-item-text', 0)->innertext();

            $resp = $client->get('https://od.globaluni.ru'. $hrefRu);
            $htmlRu = HtmlDomParser::str_get_html($resp->getBody());
            $resp = $client->get('https://od.globaluni.ru'. $hrefEn);
            $htmlEn = HtmlDomParser::str_get_html($resp->getBody());

            $date = date('Y-m-d', strtotime($htmlEn->find('.news-date-text', 0)->text()));
            $title = $htmlRu->find('.novelty-title', 0)->text();
            $content = $htmlRu->find('.novelty-content', 0)->innertext();
            $titleEn = $htmlEn->find('.novelty-title', 0)->text();
            $contentEn = $htmlEn->find('.novelty-content', 0)->innertext();

            News::create([
                'summary'=> trim($summary),
                'summary_en'=> trim($summaryEn),
                'date'=> $date,
                'title'=> trim($title),
                'content'=> trim($content),
                'title_en'=> trim($titleEn),
                'content_en'=> trim($contentEn),

            ]);


        }
    }

    public function eduLevels() {
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

        foreach ($elRu as $index => $nameRu) {
            EduLevel::firstOrCreate([
                'name'=> $nameRu,
                'name_en'=> $elEn[$index]
            ]);
        }

    }

    public function universitiesLogoFix() {
        $client = new Client([
            'base_uri'=> 'https://od.globaluni.ru',
        ]);

        $resp = $client->get('/en/?needRussian=0');
        $htmlEn = HtmlDomParser::str_get_html($resp->getBody());

        $htmlNamesEn = $htmlEn->find('#universities .pt-1');
        $htmlImagesEn = $htmlEn->find('#universities .universities-img');

        foreach ($htmlNamesEn as $index => $item) {

            $name = $htmlNamesEn[$index]->text();
            $university = University::where([
                'name_en'=> $htmlNamesEn[$index]->text(),
            ])->first();

            if ($university) {

                $tempImage = tempnam(sys_get_temp_dir(), uniqid());
                copy('https://od.globaluni.ru'. $htmlImagesEn[$index]->src, $tempImage);
                $file = \Storage::putFile(Attachment::ITEM_TYPE_UNIVERSITY, $tempImage);

                $attachment = Attachment::create([
                    'item_type'=> Attachment::ITEM_TYPE_UNIVERSITY,
                    'item_id'=> $university->id,
                    'type'=> 'logo_en',
                    'file'=> $file,
                    'name'=> basename($htmlImagesEn[$index]->src)
                ]);
            } else {
                echo "\nНе найден " . $name;
            }

        }
    }

    public function universities() {
        $client = new Client([
            'base_uri'=> 'https://od.globaluni.ru',
        ]);


        \DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        DB::table('universities')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1;');

        $resp = $client->get('/?needRussian=1');

        $htmlRu = HtmlDomParser::str_get_html($resp->getBody());
        $resp = $client->get('/en/?needRussian=0');
        $htmlEn = HtmlDomParser::str_get_html($resp->getBody());
        $htmlNamesRu = $htmlRu->find('#universities .pt-1');
        $htmlImages = $htmlRu->find('#universities .universities-img');
        $htmA = $htmlRu->find('#universities a');
        $htmlNamesEn = $htmlEn->find('#universities .pt-1');
        $htmAEn = $htmlEn->find('#universities a');

        foreach ($htmlNamesRu as $index => $item) {

            $tempImage = tempnam(sys_get_temp_dir(), uniqid());
            copy('https://od.globaluni.ru'. $htmlImages[$index]->src, $tempImage);

            $file = \Storage::putFile(Attachment::ITEM_TYPE_UNIVERSITY, $tempImage);

            $university = University::create([
                'name'=> $item->text(),
                'name_en'=> $htmlNamesEn[$index]->text(),
                'url'=> $htmA[$index]->href,
                'url_en'=> $htmAEn[$index]->href,
            ]);

            $attachment = Attachment::create([
                'item_type'=> Attachment::ITEM_TYPE_UNIVERSITY,
                'item_id'=> $university->id,
                'type'=> 'logo_ru',
                'file'=> $file,
                'name'=> basename($htmlImages[$index]->src)
            ]);
        }
    }

    public function trackAndStages() {
        $tracks = [];
        $tracks[] = Track::firstOrCreate([
            'name'=> 'Бакалавриат', 'name_en'=> 'Bachelor'
        ]);

        $tracks[] = Track::firstOrCreate([
            'name'=> 'Магистратура', 'name_en'=> 'Master'
        ]);

        $tracks[] = Track::firstOrCreate([
            'name'=> 'Аспирантура', 'name_en'=> 'Postgraduate'
        ]);

        $client = new Client([
            'base_uri'=> 'https://od.globaluni.ru',
        ]);


        $profile = Profile::firstOrCreate([
            'name'=> 'Арифметика',
            'name_en'=> 'Arithmetic',
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
                'name'=> trim($item->text()),
                'name_en'=> trim($htmlNamesEn[$index]->text()),
            ]);
            $this->stages($profile, $tracks);

        }
    }


    public function stages($profile, $tracks) {

        Stage::create([
            'track_id'=> $tracks[0]->id,
            'profile_id'=> $profile->id,
            'type'=> 'portfolio',
        ]);

        Stage::create([
            'track_id'=> $tracks[0]->id,
            'profile_id'=> $profile->id,
            'type'=> 'quiz',
        ]);

        Stage::create([
            'track_id'=> $tracks[1]->id,
            'profile_id'=> $profile->id,
            'type'=> 'portfolio',
        ]);

        Stage::create([
            'track_id'=> $tracks[1]->id,
            'profile_id'=> $profile->id,
            'type'=> 'quiz',
        ]);

        Stage::create([
            'track_id'=> $tracks[2]->id,
            'profile_id'=> $profile->id,
            'type'=> 'portfolio',
        ]);

        Stage::create([
            'track_id'=> $tracks[2]->id,
            'profile_id'=> $profile->id,
            'type'=> 'quiz',
        ]);

        Stage::create([
            'track_id'=> $tracks[2]->id,
            'profile_id'=> $profile->id,
            'type'=> 'interview',
        ]);
    }

    public function testUsers() {
        $f = Factory::create();

        for ($i = 0; $i < 150; $i++) {
            $user = User::create([
                'name'=> $f->name,
                'email'=> $f->email,
                'password'=> Hash::make('123456')
            ]);
        }
        die;
//        $user->admin()->create([
//            'roles'=> array_keys(Admin::ROLES)
//        ]);


//       $user = User::factory()->create([
//            'name'=> 'Admin 2',
//            'email'=> 'admin@opendoors.ru',
//            'password'=> Hash::make('123456')
//        ]);

//        $user->admin()->create([
//            'roles'=> array_keys(Admin::ROLES)
//        ]);

        $uIds = University::pluck('id')->toArray();

        /** @var User $user */
        $user = User::factory()->create([
            'name'=> 'Менеджер Вуза 1',
            'email'=> 'uni1@opendoors.ru',
            'password'=> Hash::make('123456')
        ]);


        $user->universityUsers()->create([
            'university_id'=> $uIds[0],
            'roles'=> [UniversityUser::ROLE_MANAGE_SITE, UniversityUser::ROLE_MANAGE_USERS]
        ]);

        $user->universityUsers()->create([
            'university_id'=> $uIds[1],
            'roles'=> [UniversityUser::ROLE_MANAGE_SITE, UniversityUser::ROLE_MANAGE_USERS, UniversityUser::ROLE_MANAGE_INTERVIEW]
        ]);


        /** @var User $user */
        $user = User::factory()->create([
            'name'=> 'Менеджер Вуза 2',
            'email'=> 'uni2@opendoors.ru',
            'password'=> Hash::make('123456')
        ]);


        $user->universityUsers()->create([
            'university_id'=> $uIds[0],
            'roles'=> [UniversityUser::ROLE_MANAGE_INTERVIEW, UniversityUser::ROLE_INTERVIEWER]
        ]);

        $user->universityUsers()->create([
            'university_id'=> $uIds[1],
            'roles'=> [UniversityUser::ROLE_INTERVIEWER]
        ]);

        $user->universityUsers()->create([
            'university_id'=> $uIds[3],
            'roles'=> [UniversityUser::ROLE_INTERVIEWER]
        ]);


    }

}

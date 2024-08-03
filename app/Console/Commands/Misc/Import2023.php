<?php

namespace App\Console\Commands\Misc;

use App\Models\Attachment;
use App\Models\Profile;
use App\Models\Quiz\Question;
use App\Models\Quiz\Quiz;
use App\Models\Quiz\Theme;
use App\Models\Track;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Heriw\LaravelSimpleHtmlDomParser\HtmlDomParser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Laravel\Prompts\Table;
use PhpOffice\PhpSpreadsheet\IOFactory;
use simplehtmldom\simple_html_dom;
use simplehtmldom\simple_html_dom_node;

class Import2023 extends Command {
    protected $signature = 'app:import-2023';

    // https://od.globaluni.ru/bitrix/admin/perfmon_row_edit.php?lang=en&table_name=b_file&pk%5BID%5D=915369


    public function handle() {
        /** @var Quiz[] $quizes */


        $this->createEnvs();

        $quizes = Quiz::where('stage' , '3')->get();

        foreach ($quizes as $quiz){
            $this->importQuiz($quiz);
        }


    }


    public function importQuiz(Quiz $quiz){

        $preprocessHtml = function($text){
            $text = htmlspecialchars_decode($text);
            return str_replace(['<strong>', '</strong>'], '', $text);
        };

        $this->info($quiz->name);

        $quiz->questions()->delete();
        $quiz->groups()->delete();

        $iContest = DB::table('import_subject_area')->whereRaw('od_id = ?', $quiz->profile_id)->first('ID');

        $iTasks = DB::table('import_task')
            ->whereRaw('season_id = 2 AND contest_id = ?', $iContest->ID)->orderBy('number')->get();

        foreach ($iTasks as $iTask) {
            $iBlockTitle = DB::table('import_task_block')->find($iTask->block_id)->title;

            $theme = Theme::whereIn('id', $quiz->groups()->pluck('theme_id'))->where('name', $iBlockTitle)->first();
            if (empty($theme)) {
                $theme = Theme::create([
                    'name' => $iBlockTitle
                ]);
            }

            $group = $quiz->groups()->create([
                'order' => $iTask->number,
                'weight' => $iTask->max_score,
                'theme_id' => $theme->id,
            ]);


            $iTaskOptionIds = DB::table('import_task_option')->whereRaw('task_id = ?', $iTask->ID)->orderBy('id')->pluck('ID');

            foreach ($iTaskOptionIds as $iTaskOptionId) {
                $warns = [];
                $iTaskContentRu = DB::table('import_task_content')
                    ->where('task_option_id', '=', $iTaskOptionId)
                    ->where('language_id', '=', '1')->first();


                $iTaskContentEn = DB::table('import_task_content')
                    ->where('task_option_id', '=', $iTaskOptionId)
                    ->where('language_id', '=', '2')->first();

                $iTaskContentRu->text = $preprocessHtml($iTaskContentRu->text);
                $iTaskContentEn->text = $preprocessHtml($iTaskContentEn->text);

                $iOptionsRu = DB::table('import_task_answer')->where('task_content_id', '=', $iTaskContentRu->ID)->get();
                $iOptionsEn = DB::table('import_task_answer')->where('task_content_id', '=', $iTaskContentEn->ID)->get();

                if ($iTask->type_id == 3) {
                    $type = Question::TYPE_MULTI;
                    $options = [];
                    $correct = [];

                    if (count($iOptionsRu) != count($iOptionsEn)) {
                        $warns[] = ('Не совпадает кол-во вариантов ответа (Rus / Eng)');
                    }

                    $isNums = true;

                    for ($i = 0; $i < min(count($iOptionsRu), count($iOptionsEn)); $i++) {

                        if ($iOptionsRu[$i]->correct != $iOptionsEn[$i]->correct) {
                            $warns[] = ('Не совпадают верные ответы (Rus / Eng)');
                        }
                        if ($iOptionsRu[$i]->correct) {
                            $correct[] = $i;
                        }
                        $options[] = [
                            'text' => $optRu = htmlspecialchars_decode($iOptionsRu[$i]->value),
                            'text_en' => $optEn = htmlspecialchars_decode($iOptionsEn[$i]->value),
                        ];

                        if(trim($optRu) != $i + 1 && trim($optEn) != $i + 1){
                            $isNums = false;
                        }
                    }

                    if (count($correct) == 0) {
                        $warns[] = ('Не указаны верные ответы');
                    }

                    if($isNums) {

                        $htmlRu = HtmlDomParser::str_get_html("<div>" . $iTaskContentRu->text . "</div>");
                        $htmlEn = HtmlDomParser::str_get_html("<div>" . $iTaskContentEn->text . "</div>");
                        $htmlOlRu = $htmlRu->find('ol', 0);
                        $htmlOlEn = $htmlEn->find('ol', 0);

                        if($htmlOlRu && $htmlOlEn){

                            $htmlLisRu = $htmlOlRu->find('li');
                            $htmlLisEn = $htmlOlEn->find('li');

                            if (count($htmlLisRu) == count($options) && count($htmlLisEn) == count($options)){
                                for ($i = 0; $i < count($options); $i++){
                                    $options[$i] = [
                                        'text' => $htmlLisRu[$i]->innertext(),
                                        'text_en' => $htmlLisEn[$i]->innertext(),
                                    ];
                                }
                                $htmlOlRu->outertext = '';
                                $htmlOlEn->outertext = '';
                                $iTaskContentRu->text = $htmlRu->firstChild()->innertext();
                                $iTaskContentEn->text = $htmlEn->firstChild()->innertext();
                                $isNums = false;
                            }
                        }
                        if($isNums){
                            $warns[] = ('Не удалось корректно импортировать пронумерованные ответы');
                        }
                    }

                    $options = [
                        'options' => $options
                    ];
                    $verification = [
                        'correct' => $correct,
                        'weightsTable' => $this->getWeightsTable(count($correct), count($options['options']), (int)$group->weight)
                    ];

                } else if ($iTask->type_id == 1) {
                    $type = Question::TYPE_WORDS;
                    $verification = ['pattern' => [], 'pattern_en' => []];
                    $options = null;
                    $hasIncorrect = false;
                    for ($i = 0; $i < min(count($iOptionsRu), count($iOptionsEn)); $i++) {
                        if ($iOptionsRu[$i]->correct) {
                            $verification['pattern'][] = htmlspecialchars_decode($iOptionsRu[$i]->value);
                        } else {
                            $hasIncorrect = true;
                        }
                        if ($iOptionsEn[$i]->correct) {
                            $verification['pattern_en'][] = htmlspecialchars_decode($iOptionsEn[$i]->value);
                        } else {
                            $hasIncorrect = true;
                        }
                    }
                    if ($hasIncorrect) {
                        $warns[] = ('Вопрос с эталонным ответом содержит некорректные опции');
                    }
                }

                $question = Question::create([
                    'quiz_id' => $quiz->id,
                    'group_id' => $group->id,
                    'status' => 'active',
                    'type' => $type,
                    'text' =>       $iTaskContentRu->text,
                    'text_en' =>    $iTaskContentEn->text,
                    'options' => $options,
                    'verification' => $verification,
                    'created_at' => '2023-01-01 00:00:00'
                ]);

                $importFiles = function($iContent, $locale) use ($question, &$warns){
                    $iFiles = DB::table('import_task_file')->where('task_content_id', '=', $iContent->ID)->get();
                    foreach ($iFiles as $iFile){

                        $tempImage = tempnam(sys_get_temp_dir(), uniqid());
                        copy($iFile->url, $tempImage);
                        $file = \Storage::putFile(Attachment::ITEM_TYPE_QUESTION, $tempImage);

                        $attachmentRu = Attachment::create([
                            'item_type'=> Attachment::ITEM_TYPE_QUESTION,
                            'item_id'=> $question->id,
                            'file'=> $file,
                            'type'=> $locale,
                            'name'=> $iFile->name
                        ]);

                        $warns[] = 'Импорт файла ' . $iFile->name;
                    }
                };
                $importFiles($iTaskContentRu, 'ru');
                $importFiles($iTaskContentEn, 'en');

                $processHtml = function($text, $imageType){
                    /** @var simple_html_dom_node $html */
                    $html = HtmlDomParser::str_get_html("<div>" . $text . "</div>");
                    $htmlImgs = $html->find('img[src]');
                    foreach ($htmlImgs as &$htmlImg){
                        echo "IMG: " . $htmlImg->src;
                    }

                    return $html->firstChild()->innertext();
                };
                $question->text =       $processHtml($question->text, 'text');
                $question->text_en =    $processHtml($question->text_en, 'text_en');

                $question->save();

                foreach ($warns as $warn){
                    $this->info($question->id . "\t" . $warn . "\thttps://od.globaluni.ru/admin/simulator/edit/task/" . $iTask->ID . '/' . $iTaskOptionId);
                }

            }
        }
    }


    public function getWeightsTable($rCount, $wCount, $max = 1) {
        $getAutoWeight = function ($r, $w) use ($rCount, $wCount, $max) {
            $mw = 1;
            if ($max && $rCount != 0) {
                $mw = $max / $rCount;
            }
            return round(max(0, ($r - $w) * $mw));
        };

        $weights = [];
        for ($r = 0; $r <= $rCount; $r++) {
            $weights[] = [];
            for ($w = 0; $w <= $wCount - $rCount; $w++) {
                $weights[$r][] = $getAutoWeight($r, $w);
            }
        }

        return $weights;
    }

    public function createEnvs() {
        foreach (Profile::active()->get() as $profile) {

            $newProfile = Quiz::firstOrCreate([
                'profile_id' => $profile->id,
                'track' => Quiz::TRACK_B,
                'stage' => Quiz::STAGE_2023], [
                'name' => join('. ', [
                    $profile->name, Quiz::TRACK_NAMES[Quiz::TRACK_B], Quiz::STAGE_NAMES[Quiz::STAGE_2023]
                ])
            ]);

            // $this->line($newProfile->id . "\t" . $newProfile->name);

        }
    }


    public function exl($name) {

        $excel = IOFactory::load(resource_path('temp/2023/' . $name . '.xls'));
        $sheet = $excel->getActiveSheet();
        $keys = [];

        $i = 1;

        for ($j = 1; !empty($value = $sheet->getCell([$j, $i])->getValue()); $j++) {
            $keys[$j] = $value;
        }

        $data = [];
        for ($i = 2; $i <= $sheet->getHighestRow(); $i++) {
            $item = [];
            foreach ($keys as $j => $key) {
                $item[$key] = $sheet->getCell([$j, $i])->getValue();
            }
            $data[] = $item;
        }

        return $data;

    }


    public function getFile($id) {

        $cookieJar = CookieJar::fromArray([
            'BITRIX_SM_LAST_SETTINGS' => '',
            'BITRIX_SM_LOGIN' => 'burovenko.dv@mipt.ru',
            'BITRIX_SM_NCC' => 'Y',
            'BITRIX_SM_NEED_RUSSIAN' => '0',
            'BITRIX_SM_SOUND_LOGIN_PLAYED' => 'Y',
            'BITRIX_SM_UIDH' => 'JUjz85fZYXfumBpAunL44TAEC9NioXth',
            'BITRIX_SM_UIDL' => 'burovenko.dv%40mipt.ru',
            'PHPSESSID' => 'a2h3W5w4YtOa90zeSqtKfxSD6susPgxq',
        ], 'od.globaluni.ru');
        $client = new Client(['cookies' => $cookieJar]);

        $itfTable = DB::table('import_task_file');
        $files = $itfTable->whereNull('url')->get();
        foreach ($files as $file) {
            echo $file->ID . "";
            $resp = $client->request('GET', 'https://od.globaluni.ru/bitrix/admin/perfmon_row_edit.php?lang=en&table_name=b_file&pk%5BID%5D=' . $file->file_id);
            $htmlRu = HtmlDomParser::str_get_html($resp->getBody()->getContents());

            $url = 'https://od.globaluni.ru/upload/' . $htmlRu->find('textarea[name=SUBDIR]', 0)->text() . '/' . $htmlRu->find('textarea[name=FILE_NAME]', 0)->text();

            $resp = $client->head($url);

            DB::table('import_task_file')->where('ID', '=', $file->ID)->update([
                'url' => 'https://od.globaluni.ru/upload/' . $htmlRu->find('textarea[name=SUBDIR]', 0)->text() . '/' . $htmlRu->find('textarea[name=FILE_NAME]', 0)->text(),
                'name' => $htmlRu->find('textarea[name=ORIGINAL_NAME]', 0)->text(),
                'width' => $htmlRu->find('input[name=WIDTH]', 0)->value,
                'height' => $htmlRu->find('input[name=HEIGHT]', 0)->value,
                'mime' => $htmlRu->find('textarea[name=CONTENT_TYPE]', 0)->text(),
                'check' => $resp->getStatusCode() == 200 ? 1 : 0
            ]);

            echo " OK\n";
        }

    }

}

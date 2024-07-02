<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\Quiz\Question;
use App\Models\Quiz\Quiz;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class QuizSeeder extends Seeder {

    public function run() {
        $this->base();
    }


    public function arifm($qg): void {


        $qg->questions()->forceDelete();
        $qg->groups()->forceDelete();

        $group = $qg->groups()->create(['weight' => 5]);

        for ($i = 0; $i < 5; $i++) {
            $c = rand(10, 100);
            $a = rand(1, $c - 1);
            $b = $c - $a;
            $vars = [$c . ' *'];
            for ($j = 1; $j < rand(2, 5); $j++) {
                $vars[] = rand(10, 120);
            }
            $vars = array_unique($vars);
            $vars = \Arr::shuffle($vars);
            $correct = array_search($c . ' *', $vars);

            $q = Question::create([
                'quiz_id' => $qg->id,
                'group_id' => $group->id,
                'text' => 'Сколько будет ' . $a . ' + ' . $b . '?',
                'text_en' => 'How much is ' . $a . ' + ' . $b . '?',
                'type' => Question::TYPE_ONE,
                'options' => [
                    'options' => array_map(fn($itm) => [
                        'text' => '<p>' . $itm . '</p>',
                        'text_en' => '<p>' . $itm . '</p>',
                    ], $vars)
                ],
                'verification' => [
                    'correct' => $correct
                ]

            ]);
        }

        $group = $qg->groups()->create(['weight' => 15]);

        for ($i = 0; $i < 5; $i++) {
            $c = rand(10, 100);

            $vars = [];
            $correct = [];
            for ($j = 0; $j < rand(2, 6); $j++) {
                $a = rand(1, $c - 1);
                if (rand(0, 1) == 0) {
                    $b = $c - $a;
                    $vars[] = $a . ' + ' . $b . ' *';
                    $correct[] = $j;
                } else {
                    $b = rand(1, $c - 1);
                    $vars[] = $a . ' + ' . $b;
                }
            }

            $q = Question::create([
                'quiz_id' => $qg->id,
                'group_id' => $group->id,
                'text' => 'Как получить ' . $c . '?',
                'text_en' => 'How to get ' . $c . '?',
                'type' => Question::TYPE_MANY,
                'options' => [
                    'options' => array_map(fn($itm) => [
                        'text' => '<p>' . $itm . '</p>',
                        'text_en' => '<p>' . $itm . '</p>',
                    ], $vars)
                ],
                'verification' => [
                    'correct' => $correct
                ]
            ]);
        }

        $group = $qg->groups()->create(['weight' => 30]);

        for ($i = 0; $i < 5; $i++) {
            $c = rand(10, 100);

            $vars = [];
            $correct = [];
            for ($j = 0; $j < rand(2, 6); $j++) {
                $a = rand(10, 100);
                if ($a < $c) {
                    $vars[] = $a . ' *';
                    $correct[] = $j;
                } else {
                    $vars[] = $a;
                }
            }

            $weights = [];
            for ($r = 0; $r <= count($correct); $r++) {
                for ($w = 0; $w <= count($vars) - count($correct); $w++) {
                    $weights[$r][$w] = max(0, $r - $w);
                }
            }
            $q = Question::create([
                'quiz_id' => $qg->id,
                'group_id' => $group->id,
                'text' => 'Укажите все числа меньше ' . $c . '?',
                'text_en' => 'Specify all numbers less than ' . $c . '?',
                'type' => Question::TYPE_MULTI,
                'options' => [
                    'options' => array_map(fn($itm) => [
                        'text' => '<p>' . $itm . '</p>',
                        'text_en' => '<p>' . $itm . '</p>',
                    ], $vars)
                ],
                'verification' => [
                    'correct' => $correct,
                    'weightsTable' => $weights
                ]
            ]);

        }

    }


    public function base(): void {
        /** @var Profile $profile */
        foreach (Profile::all() as $profile) {

            $qg = Quiz::firstOrCreate([
                'name' => $profile->name . '. Бакалавриат. Вступительный тест',
                'profile_id' =>  $profile->id,
                'track' => Quiz::TRACK_B,
                'stage' => Quiz::STAGE_1,
            ]);

            if ($profile->name == 'Арифметика') {
                $this->arifm($qg);
                continue;
            }

            $qg = Quiz::firstOrCreate([
                'name' => $profile->name . '. Бакалавриат. Тестирование II Этапа',
                'profile_id' =>  $profile->id,
                'track' => Quiz::TRACK_B,
                'stage' => Quiz::STAGE_2,
            ]);


            $qg = Quiz::firstOrCreate([
                'name' => $profile->name . '. Магистратура и Аспирантура. Вступительный тест',
                'profile_id' =>  $profile->id,
                'track' => Quiz::TRACK_MA,
                'stage' => Quiz::STAGE_1,
            ]);

            $qg = Quiz::firstOrCreate([
                'name' => $profile->name . '. Магистратура и Аспирантура. Тестирование II Этапа',
                'profile_id' =>  $profile->id,
                'track' => Quiz::TRACK_MA,
                'stage' => Quiz::STAGE_2,
            ]);
        }
    }

}

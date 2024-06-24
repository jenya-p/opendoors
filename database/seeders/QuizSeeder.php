<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Attachment;
use App\Models\EduLevel;
use App\Models\Profile;
use App\Models\QuizGroup;
use App\Models\QuizQuestion;
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

        $qg = QuizGroup::firstOrCreate([
            'name' => 'Арифметика',
        ]);

        $qg->questions()->forceDelete();
        for ($i = 0; $i < 20; $i++) {
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

            $q = QuizQuestion::create([
                'group_id' => $qg->id,
                'weight' => 1,
                'order' => 1,
                'text' => 'Сколько будет ' . $a . ' + ' . $b . '?',
                'text_en' => 'How much is ' . $a . ' + ' . $b . '?',
                'type' => QuizQuestion::TYPE_ONE,
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


        for ($i = 0; $i < 20; $i++) {
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

            $q = QuizQuestion::create([
                'group_id' => $qg->id,
                'weight' => 1,
                'order' => 2,
                'text' => 'Как получить ' . $c . '?',
                'text_en' => 'How to get ' . $c . '?',
                'type' => QuizQuestion::TYPE_MANY,
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


        for ($i = 0; $i < 20; $i++) {
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
            $q = QuizQuestion::create([
                'group_id' => $qg->id,
                'weight' => 1,
                'order' => 3,
                'text' => 'Укажите все числа меньше ' . $c . '?',
                'text_en' => 'Specify all numbers less than ' . $c . '?',
                'type' => QuizQuestion::TYPE_MULTI,
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


}

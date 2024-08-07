<?php

namespace App\Http\Controllers\Lk;

use App\Http\Requests\Lk\AchievementRequest;
use App\Models\Attachment;
use App\Models\Dir\Country;
use App\Models\EduLevel;
use App\Models\Participant\Achievement;
use App\Models\Dir\Achievement as DirAchievement;
use App\Models\Participant\Participant;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AchievementController extends \App\Http\Controllers\Controller {


    public function index(Request $request) {

        /** @var Participant $participant */
        $participant = \Auth::user()->participant;
        $items = $participant->achievements;
        $items->map(function ($itm) {
            $itm->type?->translate();
        });

        return Inertia::render('Lk/Achievement/Index', [
            'participant' => $participant,
            'items' => $items
        ]);
    }

    public function create() {
        /** @var Participant $participant */
        $participant = \Auth::user()->participant;
        $achievement = new Achievement([
            'participant_id' => \Auth::user()->participant->id,
        ]);

        $achievement->setRelation('participant', $participant);

        return Inertia::render('Lk/Achievement/Edit', [
            'type_options' => $this->getTypeOptions($achievement),
            'item' => $achievement
        ]);
    }

    public function store(AchievementRequest $achievementRequest) {
        $participant = \Auth::user()->participant;

        $data = $achievementRequest->validated();
        $data['participant_id'] = $participant->id;
        $achievement = Achievement::create($data);
        $this->storeName($achievement);
        $this->storeAttachments($achievement);

        return \Redirect::route('lk.achievement.index');
    }

    public function storeName(Achievement $achievement){
        $schema = @config('achievements')[$achievement->type->key];
        if (empty($schema)) {
            return null;
        }

        $achievement->name = '';
        $one = function (&$schema, $data) use (&$one, $achievement) {
            if ($schema['type'] == 'array') {
                foreach ($data as &$value) {
                    $one($schema['items'], $value);
                }
            } else if ($schema['type'] == 'object') {
                foreach ($schema['properties'] as $key => &$schemaItem) {
                    if (@$schemaItem['translable']) {
                        $one($schemaItem, $data[$key] ?? null);
                        $one($schemaItem, $data[$key . '_en'] ?? null);
                    } else {
                        $one($schemaItem, $data[$key] ?? null);
                    }
                }
            } else if (array_key_exists('naming', $schema) && $schema['naming']) {
                if($achievement->name !== null){
                    $achievement->name = (string)$data;
                }
            }
        };

        $one($schema, $achievement->content);


        $achievement->save();
    }

    public function storeAttachments(Achievement $achievement){
        $schema = @config('achievements')[$achievement->type->key];
        if (empty($schema)) {
            return null;
        }

        $one = function (&$schema, $data) use (&$one, $achievement) {
            if ($schema['type'] == 'attachment') {
                $schema['options'] = [];
                $atta = Attachment::find($data);
                if ($atta) {
                    $atta->update([
                        'item_id' => $achievement->id
                    ]);
                }
            } else if ($schema['type'] == 'array') {
                foreach ($data as &$value) {
                    $one($schema['items'], $value);
                }
            } else if ($schema['type'] == 'object') {
                foreach ($schema['properties'] as $key => &$schemaItem) {
                    if (@$schemaItem['translable']) {
                        $one($schemaItem, $data[$key] ?? null);
                        $one($schemaItem, $data[$key . '_en'] ?? null);
                    } else {
                        $one($schemaItem, $data[$key] ?? null);
                    }
                }
            }
        };


        $one($schema, $achievement->content);

    }

    public function edit(Achievement $achievement) {
        $achievement->type->translate();
        return Inertia::render('Lk/Achievement/Edit', [
            'type_options' => $this->getTypeOptions($achievement),
            'item' => $achievement
        ]);
    }


    public function update(Achievement $achievement, AchievementRequest $achievementRequest) {
        $achievement->update($achievementRequest->validated());
        $this->storeName($achievement);
        $this->storeAttachments($achievement);
        return \Redirect::route('lk.achievement.index');
    }

    public function destroy(Achievement $achievement) {
        $achievement->delete();
        return ['result' => 'ok'];
    }

    public function getTypeOptions($achievement) {
        $options = DirAchievement::get()->translate();
        foreach ($options as $option) {
            $option->schema = $this->getSchema($option->key, $achievement);
        }
        return $options;
    }

    public function getSchema($key, Achievement $achievement) {
        $schema = @config('achievements')[$key];
        if (empty($schema)) {
            return null;
        }
        $one = function (&$schema, $data) use (&$one, $achievement) {

            if ($schema['type'] == 'attachment') {
                $schema['options'] = [];
                $atta = Attachment::find($data);

                if ($atta) {
                    if (empty($schema['options'])) {
                        $schema['options'] = [Attachment::find($data)];
                    } else {
                        $schema['options'][] = Attachment::find($data);
                    }
                }
                if (empty($schema['params'])) {
                    $schema['params'] = [
                        'item_type' => Attachment::ITEM_TYPE_ACHIEVEMENT, 'item_id' => $achievement->id,
                    ];
                }
            } else if ($schema['type'] == 'array') {
                foreach ($data as &$value) {
                    $one($schema['items'], $value);
                }
            } else if ($schema['type'] == 'object') {
                foreach ($schema['properties'] as $key => &$schemaItem) {
                    if (@$schemaItem['translable']) {
                        $one($schemaItem, $data[$key] ?? null);
                        $one($schemaItem, $data[$key . '_en'] ?? null);
                    } else {
                        $one($schemaItem, $data[$key] ?? null);
                    }
                }
            } else if ($schema['type'] == 'select') {
                $schema['options'] = $this->buildOptions($schema['options']);
            }
        };


        $one($schema, $achievement->content);

        return $schema;
    }

    public function buildOptions($options) {
        if ($options == 'language_levels_ru') {
            $src = [
                "RUSSIAN_LEVEL_1",
                "RUSSIAN_LEVEL_2",
                "RUSSIAN_LEVEL_3",
                "RUSSIAN_LEVEL_4",
                "RUSSIAN_LEVEL_5",
            ];
        } else if ($options == 'language_levels_en') {
            $src = [
                "ENGLISH_LEVEL_1",
                "ENGLISH_LEVEL_2",
                "ENGLISH_LEVEL_3",
                "ENGLISH_LEVEL_4",
                "ENGLISH_LEVEL_5",
                "ENGLISH_LEVEL_6",
                "ENGLISH_LEVEL_7",
            ];
        } else if ($options == 'publication_types') {
            $src = [
                "PUBLICATION_TYPES_1",
                "PUBLICATION_TYPES_2",
                "PUBLICATION_TYPES_OTHER",
            ];
        } else {
            return [];
        }

        $options = [];
        foreach ($src as $srci) {
            $options[] = [
                'id' => $srci,
                'name' => trans("od.OPTIONS." . $srci)
            ];
        }
        return $options;

    }
}

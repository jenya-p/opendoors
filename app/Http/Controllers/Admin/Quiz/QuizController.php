<?php

namespace App\Http\Controllers\Admin\Quiz;

use App\Http\Controllers\Controller;
use App\Http\Requests\Quiz\QuizRequest;
use App\Models\Quiz\Group;
use App\Models\Quiz\Quiz;
use App\Models\Quiz\Theme;
use Inertia\Inertia;

class QuizController extends Controller
{

    public function index()
    {
        $quizzes = Quiz::with('profile:id,name', 'groups:id,order,weight')->get();
        $quizzes->load('groups:id,quiz_id,order,weight');
        $quizzes->each->append('question_count', 'track_name', 'stage_name');
        $quizzes->map(function(Quiz $quiz) {$quiz->group_count = count($quiz->groups);});

        return Inertia::render('Admin/Quiz/Quiz/Index', [
            'items' =>$quizzes
        ]);
    }



    public function edit(Quiz $quiz) {
        $quiz->load('profile:id,name', 'groups', 'groups.theme');
        $quiz->append('question_count', 'track_name', 'stage_name');
        $quiz->groups->each->append('question_count');
        return Inertia::render('Admin/Quiz/Quiz/Edit', [
            'item' => $quiz,
            'theme_options' => fn() => $this->getThemeOptions($quiz)
        ]);
    }


    public function update(QuizRequest $request, Quiz $quiz) {
        // $quiz->update($request->validated());
        $this->updateGroups($quiz, $request->groups);

        return \Redirect::route('admin.quiz.index');
    }

    public function getThemeOptions(Quiz $quiz){
        return Theme::whereIn('id', $quiz->groups()->pluck('theme_id'))->get()->toArray();
    }

    protected function updateGroups(Quiz $quiz, $groups){

        $themeCache = [];
        $saveTheme = function($theme) use (&$themeCache){
            if(empty($theme)) return null;
            if(!empty($theme['id'])) return $theme['id'];
            if(empty($theme['name'])) return null;

            if(array_key_exists($theme['name'], $themeCache)) return $themeCache[$theme['name']];
            $theme = Theme::create(['name' => $theme['name']]);
            $themeCache[$theme['name']] = $theme->id;
            return $theme->id;
        };


        $idsToDelete = $quiz->groups()->pluck('id')->toArray();
        foreach ($groups as $index => $group){
            $dbGroup = null;
            if(!empty($group['id'])){
                $id = $group['id'];
                $dbGroup = $quiz->groups()->find($id);
				if (($key = array_search($id, $idsToDelete)) !== false) {
                    unset($idsToDelete[$key]);
                }
            }
            if(!empty($dbGroup)){
                $dbGroup->order = $index + 1;
                $dbGroup->weight = $group['weight'];
                $dbGroup->theme_id = $saveTheme($group['theme']);
                $dbGroup->save();
            } else {
                $dbGroup = $quiz->groups()->create([
                    'order' => $index + 1,
                    'weight' => $group['weight'],
                    'theme_id' => $saveTheme($group['theme'])
                ]);
            }
        }

        foreach ($idsToDelete as $id){
            $quiz->groups()->find($id)->delete();
        }
    }

    public function destroy(Quiz $quiz) {
        $quiz->delete();
        return ['result' => 'ok'];
    }
}

<?php

namespace App\Http\Controllers\Admin\Quiz;

use App\Http\Controllers\Controller;
use App\Http\Requests\Quiz\QuizRequest;
use App\Models\Profile;
use App\Models\Quiz\Group;
use App\Models\Quiz\Quiz;
use App\Models\Quiz\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Inertia\Inertia;

class QuizController extends Controller
{

    public function index(Request $request)
    {
        $filter = $request->only(['profile_id', 'track', 'stage', 'query']);

        $query = Quiz::with('profile:id,name', 'groups:id,order,weight');
        $query->filter($filter);

        if(!empty($request->sort)){
            list($name, $dir) = explode(':', $request->sort);
            if($name == 'profile'){
                $query->leftJoin('profiles', 'profiles.id', '=', 'quizzes.profile_id');
                $query->orderBy('profiles.name', $dir);
            } else if(!in_array($name, ['question_count','group_count'])){
                $query->orderBy($name, $dir);
            }
        }
        $query->orderBy('id');

        $quizzes =  $query->get();
        $quizzes->load('groups:id,quiz_id,order,weight');
        $quizzes->each->append('question_count', 'track_name', 'stage_name');
        $quizzes->map(function(Quiz $quiz) {$quiz->group_count = count($quiz->groups);});


        if(!empty($request->sort)){
            list($name, $dir) = explode(':', $request->sort);
            if($name == 'question_count'){
                $quizzes = $quizzes->sortBy('question_count', SORT_REGULAR, $dir == 'desc')->values();
            } else if($name == 'group_count'){
                $quizzes = $quizzes->sortBy('group_count', SORT_REGULAR, $dir == 'desc')->values();
            }
        }

        if(!$request->inertia() && $request->isXmlHttpRequest()){
            return [
                'items' => $quizzes,
            ];
        } else {
            return Inertia::render('Admin/Quiz/Quiz/Index', [
                'items' => $quizzes,
                'profile_options' => Profile::get(['id', 'name'])->toArray(),
                'theme_options' => Theme::get(['id', 'name'])->toArray(),
                'track_options' => Arr::assocToOptions(Quiz::TRACK_NAMES),
                'stage_options' => Arr::assocToOptions(Quiz::STAGE_NAMES),
                'filter' => $filter,
                'sort' => $request->sort
            ]);
        }
    }


    public function edit(Quiz $quiz) {
        $quiz->load('profile:id,name', 'groups', 'groups.theme', 'roles', 'roles.user:id,name');
        $quiz->append('question_count', 'track_name', 'stage_name');
        $quiz->groups->each->append('question_count');
        return Inertia::render('Admin/Quiz/Quiz/Edit', [
            'item' => $quiz,
            'theme_options' => fn() => $this->getThemeOptions($quiz),
            'role_options' => Arr::assocToOptions(Quiz::ROLE_NAMES)
        ]);
    }


    public function update(QuizRequest $request, Quiz $quiz) {
        // $quiz->update($request->validated());
        $this->updateGroups($quiz, $request->groups);
        $quiz->updateRoles($request->roles);
        return \Redirect::route('admin.quiz.edit', $quiz);
    }

    public function getThemeOptions(Quiz $quiz){
        return Theme::whereIn('id', $quiz->groups()->pluck('theme_id'))->get()->toArray();
    }

    protected function updateGroups(Quiz $quiz, $groups){

        $themeCache = [];
        $saveTheme = function($theme) use (&$themeCache){
            if(empty($theme)) return null;
            if(!empty($theme['id'])) {
                $dbTheme = Theme::find($theme['id']);
                if($dbTheme){
                    $dbTheme->update(['name' => $theme['name']]);
                    return $dbTheme->id;
                }
                return null;
            }
            if(empty($theme['name'])) return null;
            if(array_key_exists($theme['name'], $themeCache)) return $themeCache[$theme['name']];
            $dbTheme = Theme::create(['name' => $theme['name']]);
            $themeCache[$theme['name']] = $dbTheme->id;
            return $dbTheme->id;
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

<?php

namespace App\Http\Controllers\Admin\Quiz;

use App\Http\Controllers\Controller;
use App\Http\Requests\Quiz\QuestionRequest;
use App\Models\Attachment;
use App\Models\Profile;
use App\Models\Quiz\Group;
use App\Models\Quiz\Question;
use App\Models\Quiz\Quiz;
use App\Models\Quiz\Theme;
use App\Models\Track;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Inertia\Inertia;

class QuestionController extends Controller
{
    public function index(Request $request)
    {

        $query = Question::available()
            ->select('quiz_questions.*')
            ->with([
                'quiz:id,name',
                'group:id,theme_id,order,weight',
                'group.theme:id,name',
            ]);

        $filter = $request->only('track', 'profile_id', 'stage', 'theme_id', 'status','query');
        $themeOptions = $this->getThemeOptions($filter);
        $query->filter($filter);
        $sort = null;
        if(!empty($request->sort)){
            list($name, $dir) = explode(':', $request->sort);
            $sort = ['name' => $name, 'dir' => $dir];

            if($name == 'quiz'){
                $query->leftJoin('quizzes', 'quizzes.id', '=', 'quiz_questions.quiz_id');
                $query->orderBy('quizzes.name', $dir);
            } else if($name == 'order' or $name == 'weight'){
                $query->leftJoin('quiz_groups', 'quiz_groups.id', '=', 'quiz_questions.group_id');
                if($name == 'order'){
                    $query->orderBy('quiz_groups.order', $dir);
                } else {
                    $query->orderBy('quiz_groups.weight', $dir);
                }
            } else {
                $query->orderBy($name, $dir);
            }
        }
        $query->orderBy('id', 'asc');

        $items = $query->paginate(10);

        $items->append(['snippet', 'option_count', 'status_name', 'can']);

        if(!$request->inertia() && $request->isXmlHttpRequest()){
            return [
                'items' => $items,
                'theme_options' => $themeOptions,
                'filter' => $filter
            ];
        } else {
            return Inertia::render('Admin/Quiz/Question/Index', [
                'profile_options' => Profile::available()->get(['id', 'name'])->toArray(),
                'theme_options' => $themeOptions,
                'track_options' => Arr::assocToOptions(Quiz::TRACK_NAMES),
                'stage_options' => Arr::assocToOptions(Quiz::STAGE_NAMES),
                'status_options' => $this->getAvailableStatusOptions(),
                'filter' => $filter + ['sort' => $sort],
                'items' => $items
            ]);
        }

    }

    public function getThemeOptions(&$filter){
        if(empty($filter['profile_id'])){
            return [];
        }
        $quizIds = Quiz::select('id')->filter(Arr::only($filter, 'profile_id'))->get()->toArray();
        if(empty($quizIds)){
            return [];
        }
        $themeIds = Group::select('theme_id')->whereIn('quiz_id', $quizIds)->get()->toArray();
        if(empty($themeIds)){
            return [];
        }
        return Theme::find($themeIds, ['id', 'name'])->toArray();

    }

    public function create() {
        return Inertia::render('Admin/Quiz/Question/Edit', [
            'type_options' => $this->getAvailableTypeOptions(),
            'status_options' => $this->getAvailableStatusOptions(),
            'quiz_options' => Quiz::available('edit')->with('groups', 'groups.theme')->get(['id', 'name'])->toArray(),
            'item' => new Question()
        ]);
    }

    public function store(QuestionRequest $request) {
        $question = Question::create($request->validated());

        Attachment::updateItemId($request->get('images'), $question->id);
        Attachment::updateItemId($request->get('images_en'), $question->id);

        if(request('preview')){
            return \Redirect::route('admin.quiz-probe.probe', ['question' => $question]);
        } else {
            return \Redirect::route('admin.quiz-question.edit',$question);
        }

    }

    public function show(Question $question) {
        $question->load(['images','images_en', 'quiz', 'group', 'group.theme']);
        $question->append('type_name', 'can');
        return Inertia::render('Admin/Quiz/Question/Show', [
            'item' => $question,
        ]);
    }

    public function edit(Question $question) {
        $question->load(['images','images_en']);
        return Inertia::render('Admin/Quiz/Question/Edit', [
            'type_options' => $this->getAvailableTypeOptions(),
            'status_options' => $this->getAvailableStatusOptions(),
            'quiz_options' => Quiz::available('edit')->with('groups', 'groups.theme')->get(['id', 'name'])->toArray(),
            'item' => $question,
        ]);
    }


    public function update(QuestionRequest $request, Question $question) {
        $question->update($request->validated());
        if(request('preview')){
            return \Redirect::route('admin.quiz-probe.probe', ['question' => $question]);
        } else {
            return \Redirect::route('admin.quiz-question.edit',$question);
        }
    }


    public function destroy(Question $question) {
        $question->delete();
        return ['result' => 'ok'];
    }

    public function getAvailableTypeOptions(){

        return Arr::assocToOptions(array_intersect_key(Question::TYPE_NAMES, [
            Question::TYPE_MULTI => 1,
            Question::TYPE_WORDS => 1,
            Question::TYPE_NUMBER => 1,
            Question::TYPE_FREE => 1,
        ]));

    }

    public function getAvailableStatusOptions(){

        return Arr::assocToOptions(array_intersect_key(Question::STATUS_NAMES, [
            Question::STATUS_ACTIVE => 1,
            Question::STATUS_DISABLED => 1,
            Question::STATUS_DRAFT => 1,
        ]));

    }

}

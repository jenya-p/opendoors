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

        $query = Question::query()
            ->select('quiz_questions.*')
            ->with([
                'quiz:id,name',
                'group:id,order,weight',
            ]);

        $filter = $request->only('track', 'profile_id', 'stage', 'theme_id', 'query');
        $query->filter($filter);

        if(!empty($request->sort)){
            list($name, $dir) = explode(':', $request->sort);
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


        $items->append(['snippet', 'option_count']);

        if(!$request->inertia() && $request->isXmlHttpRequest()){
            return [
                'filter' => $filter,
                'items' => $items,
            ];
        } else {
            return Inertia::render('Admin/Quiz/Question/Index', [
                'profile_options' => Profile::get(['id', 'name'])->toArray(),
                'theme_options' => Theme::get(['id', 'name'])->toArray(),
                'track_options' => Arr::assocToOptions(Quiz::TRACK_NAMES),
                'stage_options' => Arr::assocToOptions(Quiz::STAGE_NAMES),
                'filter' => $filter,
                'items' => $items
            ]);
        }

    }

    public function create() {
        return Inertia::render('Admin/Quiz/Question/Edit', [
            'type_options' => $this->getAvailableTypeOptions(),
            'quiz_options' => Quiz::with('groups', 'groups.theme')->get(['id', 'name'])->toArray(),
            'item' => new Question()

        ]);
    }

    public function getThemeOptions(Quiz $quiz){
        return Theme::whereIn('id', $quiz->groups()->pluck('theme_id'))->get()->toArray();
    }

    public function store(QuestionRequest $request) {
        $question = Question::create($request->validated());

        Attachment::updateItemId($request->get('images'), $question->id);
        Attachment::updateItemId($request->get('images_en'), $question->id);

        if(request('preview')){
            return \Redirect::route('admin.quiz-probe.probe', ['question' => $question]);
        } else {
            return \Redirect::route('admin.quiz-question.index');
        }

    }

    public function edit(Question $question) {
        $question->load(['images','images_en']);
        return Inertia::render('Admin/Quiz/Question/Edit', [
            'type_options' => $this->getAvailableTypeOptions(),
            'quiz_options' => Quiz::with('groups', 'groups.theme')->get(['id', 'name'])->toArray(),
            'item' => $question,
        ]);
    }


    public function update(QuestionRequest $request, Question $question) {
        $question->update($request->validated());
        if(request('preview')){
            return \Redirect::route('admin.quiz-probe.probe', ['question' => $question]);
        } else {
            return \Redirect::route('admin.quiz-question.index');
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

}

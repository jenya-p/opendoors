<?php

namespace App\Http\Controllers\Admin\Quiz;

use App\Http\Controllers\Controller;
use App\Http\Requests\Quiz\QuestionRequest;
use App\Models\Attachment;
use App\Models\Quiz\Group;
use App\Models\Quiz\Question;
use App\Models\Quiz\Quiz;
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
        $filter = trim($request->filter);
        if(!empty($filter)){
            $lcQuery = '%' . mb_strtolower(trim($filter)) . '%';
            if(is_numeric($filter)){
                $query->where(function(Builder $query) use ($filter, $lcQuery){
                    $query->where('id', $filter)
                        ->orWhereRaw('text like ? or text_en like ?', [$lcQuery,$lcQuery])
                        ->orderByRaw('id = ? DESC', $filter);
                });
            } else {
                $query
                    ->whereRaw('text like ? or text_en like ?', [$lcQuery,$lcQuery]);
            }
        }

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

        $items = $query->paginate(50);


        $items->append(['snippet', 'option_count']);

        return Inertia::render('Admin/Quiz/Question/Index', [
            'items' => $items
        ]);
    }

    public function create() {
        return Inertia::render('Admin/Quiz/Question/Edit', [
            'type_options' => Arr::assocToOptions(Question::TYPE_NAMES),
            'quiz_options' => Quiz::with('groups')->get(['id', 'name'])->toArray(),
            'item' => new Question()

        ]);
    }


    public function store(QuestionRequest $request) {
        $question = Question::create($request->validated());

        Attachment::updateItemId($request->get('images'), $question->id);
        Attachment::updateItemId($request->get('images_en'), $question->id);

        return \Redirect::route('admin.quiz-question.index');
    }

    public function edit(Question $question) {
        $question->load(['images','images_en']);
        return Inertia::render('Admin/Quiz/Question/Edit', [
            'type_options' => \Arr::assocToOptions(Question::TYPE_NAMES),
            'quiz_options' => Quiz::with('groups')->get(['id', 'name'])->toArray(),
            'item' => $question,
        ]);
    }


    public function update(QuestionRequest $request, Question $question) {
        $question->update($request->validated());
        return \Redirect::route('admin.quiz-question.index');
    }


    public function destroy(Question $question) {
        $question->delete();
        return ['result' => 'ok'];
    }
}

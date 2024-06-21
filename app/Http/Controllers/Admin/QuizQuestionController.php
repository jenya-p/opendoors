<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuizQuestionRequest;
use App\Models\Attachment;
use App\Models\QuizGroup;
use App\Models\QuizQuestion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Inertia\Inertia;

class QuizQuestionController extends Controller
{
    public function index(Request $request)
    {

        $query = QuizQuestion::query()
            ->select('quiz_questions.*')
            ->with([
                'group:id,name',
            ]);
        $filter = trim($request->filter);
        if(!empty($filter)){
            $lcQuery = '%' . mb_strtolower(trim($filter)) . '%';
            $query->whereRaw('text like ? or text_en like ?', [$lcQuery,$lcQuery]);
        }

        if(!empty($request->sort)){
            list($name, $dir) = explode(':', $request->sort);
            if($name == 'group'){
                $query->leftJoin('quiz_groups', 'quiz_groups.id', '=', 'quiz_questions.group_id');
                $query->orderBy('quiz_groups.name', $dir);
            } else {
                $query->orderBy($name, $dir);
            }
        }
        $query->orderBy('id', 'asc');

        $items = $query->paginate(50);


        $items->append(['snippet', 'option_count']);

        return Inertia::render('Admin/QuizQuestion/Index', [
            'items' => $items
        ]);
    }

    public function create() {
        return Inertia::render('Admin/QuizQuestion/Edit', [
            'type_options' => Arr::assocToOptions(QuizQuestion::TYPE_NAMES),
            'group_options' => QuizGroup::all('id', 'name')->toArray(),
            'item' => new QuizQuestion()

        ]);
    }


    public function store(QuizQuestionRequest $request) {
        $quizQuestion = QuizQuestion::create($request->validated());

        Attachment::updateItemId($request->get('images'), $quizQuestion->id);
        Attachment::updateItemId($request->get('images_en'), $quizQuestion->id);

        return \Redirect::route('admin.quiz-question.index');
    }

    public function edit(QuizQuestion $quizQuestion) {
        $quizQuestion->load(['images','images_en']);
        return Inertia::render('Admin/QuizQuestion/Edit', [
            'type_options' => \Arr::assocToOptions(QuizQuestion::TYPE_NAMES),
            'group_options' => QuizGroup::all('id', 'name')->toArray(),
            'item' => $quizQuestion,
        ]);
    }


    public function update(QuizQuestionRequest $request, QuizQuestion $quizQuestion) {
        $quizQuestion->update($request->validated());
        return \Redirect::route('admin.quiz-question.index');
    }


    public function destroy(QuizQuestion $quizQuestion) {
        $quizQuestion->delete();
        return ['result' => 'ok'];
    }
}

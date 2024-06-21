<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuizGroupRequest;
use App\Models\QuizGroup;
use Illuminate\Http\Request;
use Inertia\Inertia;

class QuizGroupController extends Controller
{

    public function index()
    {
        return Inertia::render('Admin/QuizGroup/Index', [
            'items' => QuizGroup::all()->append('question_count')
        ]);
    }

    public function create() {
        return Inertia::render('Admin/QuizGroup/Edit', ['item' => new QuizGroup()]);
    }


    public function store(QuizGroupRequest $request) {
        $quizGroup = QuizGroup::create($request->validated());
        return \Redirect::route('admin.quiz-group.index');
    }

    public function edit(QuizGroup $quizGroup) {
        return Inertia::render('Admin/QuizGroup/Edit', [
            'item' => $quizGroup,
        ]);
    }


    public function update(QuizGroupRequest $request, QuizGroup $quizGroup) {
        $quizGroup->update($request->validated());
        return \Redirect::route('admin.quiz-group.index');
    }


    public function destroy(QuizGroup $quizGroup) {
        $quizGroup->delete();
        return ['result' => 'ok'];
    }
}

<?php

namespace App\Http\Controllers\Admin\Quiz;

use App\Http\Controllers\Controller;
use App\Http\Requests\Quiz\ProbeRequest;
use App\Models\Quiz\Answer;
use App\Models\Quiz\Question;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProbeController extends Controller {

    public function probe(Request $request, Question $question) {

        \Gate::authorize('probe', $question);

        $question->load('images', 'images_en');
        $question->append('can');
        $question->translate();
        return Inertia::render('Admin/Quiz/Probe/Probe', [
            'question' => $question,
            'time' => now()->addHour()
        ]);
    }

    public function check(ProbeRequest $request){

        $question = Question::find($request->question);
        \Gate::authorize('probe', $question);

        $solution = $request->solution;

        $locale = $request->getLocale();

        if($question->type == Question::TYPE_ONE){
            if(isset($question->verification['correct']) && $question->verification['correct'] === $solution['choice']){
                $score = $question->weight;
                $result = Answer::RESULT_COMPLETE;
            } else {
                $score = 0;
                $result = Answer::RESULT_FAILED;
            }
        }

        return Inertia::render('Admin/Quiz/Probe/Result', [
            'question' => $question,
            'solution' => $solution,
            'score' => 1
        ]);
    }


    public function show() {
        return Inertia::render('Admin/Quiz/Probe/Result', [
            'item' => new Question()
        ]);
    }

}

<?php

namespace App\Http\Controllers\Lk;

use App\Http\Requests\Lk\StatementRequest;
use App\Models\Dir\KnowledgeArea;
use App\Models\EduLevel;
use App\Models\Participant\Degree;
use App\Models\Participant\Member;
use App\Models\Participant\Statement;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StatementController extends \App\Http\Controllers\Controller {


    public function index(\Request $request) {

        $participant = \Auth::user()->participant;
        foreach ($participant->members as $member) {
            $member->track->translate();
            $member->profile->translate();
            $member->load('statement');
        }

        return Inertia::render('Lk/Statement/Index', [
            'participant' => $participant,
        ]);
    }

    public function create(Request $request) {
        $request->validate([
            'member_id' => 'required|exists:members,id'
        ]);
        $item = new Statement([
            'member_id' => $request->member_id,
        ]);
        $member = Member::findOrFail($request->member_id);
        $member->track->translate();
        $member->profile->translate();

        $item->setRelation('member', $member);

        return Inertia::render('Lk/Statement/Edit', [
            'knowledge_tree' => KnowledgeArea::tree($member->profile),
            'item' => $item
        ]);
    }

    public function store(StatementRequest $statementRequest) {
        $statement = Statement::create($statementRequest->all());
        $statement->areas()->sync(array_unique($statementRequest->area_ids));
        return \Redirect::route('lk.statement.index');
    }

    public function edit(Statement $statement) {

        $statement->member->track->translate();
        $statement->member->profile->translate();
        $statement->append('area_ids');



        return Inertia::render('Lk/Statement/Edit', [
            'knowledge_tree' => KnowledgeArea::tree($statement->member->profile),
            'item' => $statement
        ]);
    }

    public function update(Statement $statement, StatementRequest $statementRequest) {
        $statement->update($statementRequest->all());
        $statement->areas()->sync(array_unique($statementRequest->area_ids));
        return \Redirect::route('lk.statement.index');
    }

    public function destroy(Statement $statement) {
        $statement->delete();
        return ['result' => 'ok'];
    }

}

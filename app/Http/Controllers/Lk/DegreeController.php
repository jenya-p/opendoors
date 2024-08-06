<?php
namespace App\Http\Controllers\Lk;

use App\Http\Requests\Lk\DegreeRequest;
use App\Models\Attachment;
use App\Models\Dir\Country;
use App\Models\EduLevel;
use App\Models\Participant\Degree;
use App\Models\Participant\Participant;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DegreeController extends \App\Http\Controllers\Controller {


    public function index(Request $request){

        $participant = \Auth::user()->participant;
        $participant->eduLevels->translate();
        $items = $participant->degrees;
        $items->map(function($itm){
            $itm->eduLevel?->translate();
            $itm->country?->translate();
        });

        return Inertia::render('Lk/Degree/Index', [
            'participant' => $participant,
            'items' => $items
        ]);
    }

    public function create(Request $request){
        $request->validate([
            'edu_level_id' => 'required|exists:edu_levels,id'
        ]);
        $item = new Degree([
            'edu_level_id' => $request->edu_level_id,
        ]);
        $item->setRelation('eduLevel', EduLevel::find($request->edu_level_id)->translate());

        return Inertia::render('Lk/Degree/Edit', [
            'country_options' => $this->getCountryOptions(),
            'item' => $item
        ]);
    }

    public function store(DegreeRequest $degreeRequest){
        $participant = \Auth::user()->participant;
        $existsDegreesCount = $participant->degrees()->where('edu_level_id', '=', $degreeRequest->edu_level_id)->count();
        /** @var EduLevel $eduLevel */
        $eduLevel = EduLevel::findOrFail($degreeRequest->edu_level_id)->translate();
        if(!$eduLevel->multiple && $existsDegreesCount > 0){
            return \Redirect::route('lk.degree.index')->with('message', _('Повторное получение образования на этом уровне невозможно'));
        }

        $data = $degreeRequest->validated();
        $data['participant_id'] = $participant->id;
        $degree = Degree::create($data);
        Attachment::updateItemId($degreeRequest->attachments, $degree->id);
        return \Redirect::route('lk.degree.index');
    }

    public function edit(Degree $degree){
        $degree->load('eduLevel', 'attachments');
        $degree->eduLevel->translate();
        return Inertia::render('Lk/Degree/Edit', [
            'country_options' => $this->getCountryOptions(),
            'item' => $degree
        ]);
    }

    public function getCountryOptions(){
        $lSuffix = app()->getLocale() == 'en' ? '_en' : '';
        return Country::orderBy('name' . $lSuffix)->get(['id', 'name', 'name_en'])->translate();
    }

    public function update(Degree $degree, DegreeRequest $degreeRequest){
        $participant = \Auth::user()->participant;
        $existsDegreesCount = $participant->degrees()
            ->where('id', '!=', $degree->id)
            ->where('edu_level_id', '=', $degreeRequest->edu_level_id)->count();
        /** @var EduLevel $eduLevel */
        $eduLevel = EduLevel::findOrFail($degreeRequest->edu_level_id)->translate();
        if(!$eduLevel->multiple && $existsDegreesCount > 0){
            return \Redirect::route('lk.degree.index')->with('message', _('Повторное получение образования на этом уровне невозможно'));
        }

        $degree->update($degreeRequest->validated());
        return [
          'item' => $degree
        ];
    }

    public function destroy(Degree $degree){
        $degree->delete();
        return ['result' => 'ok'];
    }



}

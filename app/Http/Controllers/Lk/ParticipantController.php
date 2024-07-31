<?php

namespace App\Http\Controllers\Lk;


use App\Models\Participant\Participant;
use App\Models\University;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ParticipantController extends \App\Http\Controllers\Controller {

    public function dashboard() {


        return Inertia::render('Lk/Dashboard');

    }


    public function universitiesEdit(Request $request) {

        /** @var User $user */
        $user = Auth::user();

        return Inertia::render('Lk/Universities', [
                'university_options' => University::get(['id', 'name', 'name_en'])->translate(),
                'items' => $user->participant->universities()->pluck('university_id')
            ]
        );
    }

    public function universitiesUpdate(Request $request) {

        /** @var Participant $participant */
        $participant = Auth::user()->participant;

        $request->validate([
            'items' => 'nullable|array',
            'items.*' => 'required|integer|exists:universities,id',
        ]);

        $savedData = $participant->universities;
        foreach ($request->items as $order => $id) {
            $finded = false;
            foreach ($savedData as $savedItem) {
                if ($savedItem->university_id == $id) {
                    $savedItem->update(['order' => $order + 1]);
                    $finded = true;
                    break;
                }
            }
            if (!$finded) {
                \App\Models\Participant\University::create([
                    'participant_id' => $participant->id,
                    'university_id' => $id,
                    'order' => $order + 1
                ]);
            }
        }
        foreach ($savedData as $savedItem) {
            if(!in_array($savedItem->university_id, $request->items)){
                $savedItem->delete();
            }
        }

        return ['result' => 'ok'];

    }


}

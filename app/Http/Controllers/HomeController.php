<?php

namespace App\Http\Controllers;

use App\Models\Content\Faq;
use App\Models\Content\FaqCategory;
use App\Models\Content\Schedule;
use App\Models\Content\Widget;
use App\Models\EduLevel;
use App\Models\Profile;
use App\Models\Scopes\Active;
use App\Models\StudyProgram;
use App\Models\University;
use App\Models\UniversityProfile;
use Inertia\Inertia;

class HomeController extends Controller {

    public function home() {

        $top = Widget::findByKey('home.top')->translate();
        $partners = Widget::findByKey('home.partners')->translate();

        return view('pages.home', [
            'top' => $top->data,
            'profiles' => Profile::all(),
            'universities' => University::whereNotNull('url')->get(),
            'schedule' => Schedule::orderBy('date_from')->get(),
            'partners' => $partners->data,
            'faqCategories' => FaqCategory::all()
        ]);

    }


    public function about() {
        $about = Widget::findByKey('about')->translate();
        return view('pages.about', $about->data);
    }


    public function rules() {
        $rules = Widget::findByKey('rules')->translate();
        $tracks = Widget::findByKey('tracks')->translate();
        return view('pages.rules', [
            'rules' => $rules->data,
            'tracks' => $tracks->data,
        ]);
    }

    public function olympiad() {
        return redirect()->route('home.rules');
    }


    public function profile(Profile $profile){

        $locale = app()->getLocale();

        $ids = \DB::select('SELECT up.program_id, up.university_id
	FROM university_profiles up
	INNER JOIN study_programs sp ON sp.id = up.program_id
	INNER JOIN universities u ON u.id = up.university_id
	WHERE
		up.profile_id = :profile AND
		sp.locale = :locale AND
		u.deleted_at is NULL AND
		sp.deleted_at is NULL
	ORDER BY sp.name, u.name
	', ['profile' => $profile->id, 'locale' => $locale]);


        $sps = StudyProgram::orderBy('name')
            ->where('locale', $locale)
            ->find(array_column($ids, 'program_id'));
        $unis = University::orderBy($locale == 'en' ? 'name_en': 'name')->find(array_column($ids, 'university_id'));

        $isOn = function($sp, $university) use ($ids){
            foreach ($ids as $idPair){
                if($idPair->program_id == $sp->id && $idPair->university_id == $university->id){
                    return true;
                }
            }
            return false;
        };

        /** @var StudyProgram $sp */
        foreach ($sps as $sp){
            $spUnis = [];
            /** @var University $university */
            foreach ($unis as $university){
                if($isOn($sp, $university)){
                    $spUnis[] = $university;
                }
            }
            $sp->setRelation('universities', $spUnis);
        }

        $studyPrograms = [];
        return view('pages.profile', [
            'profile' => $profile,
            'studyPrograms' => $sps,
            'profiles' => Profile::where('id', '!=', $profile->id)->get()
        ]);
    }

}

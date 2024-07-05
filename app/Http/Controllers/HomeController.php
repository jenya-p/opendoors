<?php

namespace App\Http\Controllers;

use App\Models\EduLevel;
use Illuminate\Http\Request;
use Illuminate\Translation\Translator;
use Inertia\Inertia;

class HomeController extends Controller {

    public function home() {

        return view('pages.home', ['test' => 'test']);

    }


    public function content($slug) {
        return Inertia::render('Public/Content', [
            'content' => \App\Models\Content::find($slug)
        ]);
    }


    public function prices() {
        return Inertia::render('Public/Prices', [
            'blocks' => EduLevel::active()->with('myActiveTests')->get()->append('available_till')
        ]);
    }



}

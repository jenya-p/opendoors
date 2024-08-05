<?php

namespace App\Http\Controllers;

use App\Models\Content\News;
use App\Models\Content\Widget;
use App\Models\EduLevel;
use Inertia\Inertia;

class NewsController extends Controller {

    public function index() {

        return view('pages.news.index', [
            'items' => News::paginate()
        ]);
    }


    public function show(News $news) {

        return view('pages.news.show', [
            'item' => $news
        ]);
    }



}

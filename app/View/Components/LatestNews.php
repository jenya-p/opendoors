<?php

namespace App\View\Components;

use App\Models\Content\News;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LatestNews extends Component {

    public function __construct(public string $title = '123123') {}

    public function render(): View|Closure|string {
        return view('components.latest-news', ['items' => News::limit(5)->orderByDesc('date')->get()]);
    }
}

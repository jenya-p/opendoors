<?php

namespace App\View\Components;

use App\Models\Content\News;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\Component;

class LatestNews extends Component {

    public function __construct(public string $title = 'Latest News', public $exceptIds = null) {}

    public function render(): View|Closure|string {

        return view('components.latest-news', [
            'items' => News::limit(5)
                ->when(!empty($this->exceptIds), fn(Builder $query) => $query->where('id', '!=', $this->exceptIds))
                ->orderByDesc('date')->get()
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\Content\NewsRequest;
use App\Models\Attachment;
use App\Models\Content\News;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class NewsController extends Controller {

    public function index(Request $request) {

        $query = News::query();

        $filter = trim($request->filter);
        if (!empty($filter)) {
            $lcQuery = '%' . mb_strtolower(trim($filter)) . '%';
            $query->whereRaw('title like ?', [$lcQuery]);
        }

        if (!empty($request->sort)) {
            list($name, $dir) = explode(':', $request->sort);
            $query->orderBy($name, $dir);
        }
        $query->orderBy('id', 'desc');
        $items = $query->paginate(50);

        return Inertia::render('Admin/Content/News/Index', [
            'items' => $items
        ]);
    }


    public function create() {
         return Inertia::render('Admin/Content/News/Edit', ['item' => new News()]);
    }


    public function store(NewsRequest $request) {
        $news = News::create($request->validated());

        Attachment::updateItemId($request->get('attachments'), $news->id);

        return \Redirect::route('admin.news.index');
    }

    public function edit(News $news) {
        return Inertia::render('Admin/Content/News/Edit', ['item' => $news]);
    }


    public function update(NewsRequest $request, News $news) {
        $news->update($request->validated());
        return \Redirect::route('admin.news.index');
    }


    public function destroy(News $news) {
        $news->delete();
        return ['result' => 'ok'];
    }

    public function status(News $news, Request $request) {
        $data = $request->validate([
            'status' => ['required', Rule::in('active', 'disabled')]
        ]);
        $news->update($data);
        return [
            'result' => 'ok',
            'item' => $news
        ];
    }

}

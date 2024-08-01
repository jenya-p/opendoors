<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\Content\WidgetRequest;
use App\Models\Content\Widget;
use App\Models\Widget\News;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WidgetController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        return Inertia::render('Admin/Content/Widget/Index', ['items' => Widget::all()->append('name')]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Widget $widget) {


        $widget->append('name');
        return Inertia::render('Admin/Content/Widget/Edit', [
            'item' => $widget,
            'schema' => $this->getSchema($widget->key)
        ]);
    }

    public function getSchema($key){
        return config('widgets')[$key];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WidgetRequest $request, Widget $widget) {
        $widget->update($request->validated());
        return \Redirect::route('admin.widget.index');
    }

}

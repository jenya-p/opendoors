<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\Content\FaqRequest;
use App\Models\Content\Faq;
use App\Models\Content\FaqCategory;
use Inertia\Inertia;

class FaqController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $items = Faq::with('category:id,name')->get();
        return Inertia::render('Admin/Content/Faq/Index', ['items' => $items]);
    }


    public function create() {
        return Inertia::render('Admin/Content/Faq/Edit', [
            'category_options' => FaqCategory::get(['id', 'name'])->toArray(),
            'item' => new \App\Models\Content\Faq()

        ]);
    }


    public function store(FaqRequest $request) {
        $faq = Faq::create($request->validated());

        return \Redirect::route('admin.faq.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Faq $faq) {
        return Inertia::render('Admin/Content/Faq/Edit', [
            'category_options' => FaqCategory::get(['id', 'name'])->toArray(),
            'item' => $faq
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FaqRequest $request, Faq $faq) {
        $faq->update($request->validated());
        return \Redirect::route('admin.faq.index');
    }

}

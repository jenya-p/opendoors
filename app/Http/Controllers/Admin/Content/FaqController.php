<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\Content\FaqRequest;
use App\Models\Content\Faq;
use App\Models\Content\FaqCategory;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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

        return \Redirect::to(route('admin.faq-category.edit', ['faq_category' => $faq->category_id]));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Faq $faq) {
        $faq->load('category:id,name');
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
        return \Redirect::to(route('admin.faq-category.edit', ['faq_category' => $faq->category_id]))->with('tab', 'faqs');
    }


    public function status(Faq $faq, Request $request) {
        $data = $request->validate([
            'status' => ['required', Rule::in('active', 'disabled')]
        ]);
        $faq->update($data);
        return [
            'result' => 'ok',
            'item' => $faq
        ];
    }

    public function updateOrder(Request $request) {
        $request->validate([
            'orders' => ['required', 'array'],
            'orders.*' => ['required', 'integer', 'exists:faqs,id'],
        ]);

        foreach ($request->orders as $index => $universityId){
            Faq::find($universityId)->update([
                'order' => (int)$index + 1
            ]);
        }

        return [
            'result' => 'ok',
        ];

    }

    public function destroy(Faq $faq) {
        $faq->delete();
        return [
            'result' => 'ok',
            'items' => $faq->category->faqs
        ];
    }

}

<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\Content\FaqCategoryRequest;
use App\Models\Content\FaqCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class FaqCategoryController extends Controller {

    private function getItems(){
        return FaqCategory::all()->append('faqs_count');
    }

    public function index(Request $request) {
        return Inertia::render('Admin/Content/FaqCategory/Index', ['items' => $this->getItems()]);
    }


    public function create() {
        return Inertia::render('Admin/Content/FaqCategory/Edit', [
            'item' => new \App\Models\Content\FaqCategory()
        ]);
    }


    public function store(FaqCategoryRequest $request) {
        $faqCategory = FaqCategory::create($request->validated());
        return \Redirect::route('admin.faq-category.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FaqCategory $faqCategory) {
        $faqCategory->load('faqs');
        return Inertia::render('Admin/Content/FaqCategory/Edit', [
            'item' => $faqCategory,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FaqCategoryRequest $request, FaqCategory $faqCategory) {
        $faqCategory->update($request->validated());
        return \Redirect::route('admin.faq-category.index');
    }

    public function status(FaqCategory $faqCategory, Request $request) {
        $data = $request->validate([
            'status' => ['required', Rule::in('active', 'disabled')]
        ]);
        $faqCategory->update($data);
        return [
            'result' => 'ok',
            'item' => $faqCategory->append('faqs_count')
        ];
    }

    public function updateOrder(Request $request) {
        $request->validate([
            'orders' => ['required', 'array'],
            'orders.*' => ['required', 'integer', 'exists:faq_categories,id'],
        ]);

        foreach ($request->orders as $index => $universityId){
            FaqCategory::find($universityId)->update([
                'order' => (int)$index + 1
            ]);
        }

        return [
            'result' => 'ok',
            'items' => $this->getItems()
        ];

    }


    public function destroy(FaqCategory $faqCategory) {
        $faqCategory->delete();
        return [
            'result' => 'ok',
            'items' =>$this->getItems()
        ];
    }

}

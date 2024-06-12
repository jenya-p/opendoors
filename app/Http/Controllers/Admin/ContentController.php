<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ContentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Admin/Content/Index', ['items' => Content::all()->append('snippet')]);
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Content $content)
    {
        return Inertia::render('Admin/Content/Edit', ['item' => $content]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Content $content)
    {
        $data = $request->validate([
            'title' => 'required|string|max:256|min:4',
            'content' => 'required|string'
        ]);

        $content->update($data);
        return \Redirect::route('admin.content.index');
    }

}

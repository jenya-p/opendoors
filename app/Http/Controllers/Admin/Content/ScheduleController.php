<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\Content\ScheduleRequest;
use App\Models\Attachment;
use App\Models\Content\Schedule;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ScheduleController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {

        $items = Schedule::orderBy('date_from')->get();
        $items->append('date_range_description');
        return Inertia::render('Admin/Content/Schedule/Index', ['items' => $items]);
    }


    public function create() {
        return Inertia::render('Admin/Content/Schedule/Edit', ['item' => new \App\Models\Content\Schedule()]);
    }


    public function store(ScheduleRequest $request) {
        $schedule = Schedule::create($request->validated());
        return \Redirect::route('admin.schedule.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $schedule) {
        return Inertia::render('Admin/Content/Schedule/Edit', ['item' => $schedule]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ScheduleRequest $request, Schedule $schedule) {
        $schedule->update($request->validated());
        return \Redirect::route('admin.schedule.index');
    }

}

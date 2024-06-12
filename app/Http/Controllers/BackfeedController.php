<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Backfeed;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BackfeedController extends Controller {

    public function create() {
        return Inertia::render('Public/Backfeed', []);
    }


    public function store(Request $request) {

        $data = $request->validate([
           'name' => 'required|string|max:126',
           'email' => 'required|email|max:126',
           'subject' => 'required|string|max:512',
           'body' => 'required|string',
           'files' => 'nullable|array',
           'files.*' => 'required|file|max:' . (20 * 1024),
        ]);

        if(\Auth::id()){
            $data['user_id'] = \Auth::id();
        }

        $backfeed = Backfeed::create($data);

        foreach ($request->file('files') as $file){
            $path = 'backfeed/' . $backfeed->id;
            $filename = \Storage::put($path, $file);
            $attachment = new Attachment([
                'name' => $file->getClientOriginalName(),
                'file' => $filename,
                'item_type' => Attachment::ITEM_TYPE_BACKFEED,
                'item_id' => $backfeed->id
            ]);
            $attachment->save();
        }

        $backfeed->refresh()->load('attachments');

        return Inertia::render('Public/Backfeed', ['status' => 'ok', 'item' => $backfeed]);
    }





}

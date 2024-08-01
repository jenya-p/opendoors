<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\Content\WidgetRequest;
use App\Models\Attachment;
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
        $widget->load('attachments');
        return Inertia::render('Admin/Content/Widget/Edit', [
            'item' => $widget,
            'schema' => $this->getSchema($widget->key, $widget)
        ]);
    }

    public function getSchema($key, Widget $widget){
        $schema = @config('widgets')[$key];
        if (empty($schema)){
            return null;
        }
        $one = function (&$schema, $data) use (&$one, $widget){
            if($schema['type'] == 'attachment'){
                $atta = Attachment::find($data);
                if($atta){
                    if(empty($schema['options'])){
                        $schema['options'] = [Attachment::find($data)];
                    } else {
                        $schema['options'][] = Attachment::find($data);
                    }
                }
                if(empty($schema['params'])){
                    $schema['params'] = [
                        'item_type' => 'widget','item_id' => $widget->id,
                    ];
                }
            } else if($schema['type'] == 'array'){
                foreach ($data as &$value){
                    $one($schema['items'], $value);
                }
            } else if($schema['type'] == 'object'){
                foreach ($schema['properties'] as $key => &$schemaItem){
                    if(@$schemaItem['translable']){
                        $one($schemaItem, $data[$key] ?? null );
                        $one($schemaItem, $data[$key.'_en'] ?? null);
                    } else {
                        $one($schemaItem, $data[$key] ?? null);
                    }
                }
            }
        };


        $one($schema, $widget->data);

        return $schema;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WidgetRequest $request, Widget $widget) {
        $widget->update(["data" => $request->get('content')]);
        return \Redirect::route('admin.widget.index');
    }

}

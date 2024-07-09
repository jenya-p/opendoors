<?php

namespace App\Http\Requests\Content;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleRequest extends FormRequest {


    public function rules(): array {
        return [

            'date_from' => 'required|date_format:Y-m-d',
            'date_to' => 'nullable|date_format:Y-m-d',
            'title' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'summary_en' => 'nullable|string',
            'details' => 'nullable|string',
            'details_en' => 'nullable|string',

        ];
    }
}

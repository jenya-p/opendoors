<?php

namespace App\Http\Requests\Content;

use App\Models\ProfileFileType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileFileTypeRequest extends FormRequest {


    public function rules(): array {
        return [
            'type' => ['required', Rule::in(array_keys(ProfileFileType::TYPES))],
            'tracks' => 'nullable|array',
            'tracks.*' => 'required|array',
            'tracks.*.id' => 'required|integer|exists:tracks,id',
            'name' => 'required|string|max:256',
            'summary' => 'nullable|string',
            'name_en' => 'required|string|max:256',
            'summary_en' => 'nullable|string',
        ];
    }
}

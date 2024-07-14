<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest {


    public function rules(): array {
        return [
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'files' => 'nullable|array',
            'files.*' => 'required|array',
            'files.*.id' => 'nullable|exists:profile_files,id',
            'files.*.type_id' => 'required|exists:profile_file_types,id',
            'files.*.file_id' => 'nullable|exists:attachments,id',
            'files.*.file_en_id' => 'nullable|exists:attachments,id',
            'files.*.active' => 'required|boolean',
        ];
    }
}

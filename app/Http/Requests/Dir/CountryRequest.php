<?php

namespace App\Http\Requests\Dir;

use Illuminate\Foundation\Http\FormRequest;

class CountryRequest extends FormRequest {


    public function rules(): array {
        return [
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'code' => 'nullable|string|max:8',
            'region_id' => 'nullable|exists:dir_regions,id'
        ];
    }
}

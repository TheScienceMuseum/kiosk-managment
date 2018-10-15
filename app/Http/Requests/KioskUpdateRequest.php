<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KioskUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user() && $this->user()->can('view all kiosks');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|unique:kiosks,name',
            'location' => 'required|string',
            'asset_tag' => '',
        ];
    }
}

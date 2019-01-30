<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PackageVersionSearchAssetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('view', $this->route('packageVersion'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'sometimes|in:image,video,',
            'filename' => 'sometimes|string|nullable',
        ];
    }
}

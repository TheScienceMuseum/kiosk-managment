<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PackageVersionValidRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->route('packageVersion'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
//            "aspect_ratio" => [
//                'required',
//                Rule::in(['16:9', '9:16']),
//            ],
//            "main" => "required|string",
//            "name" => "required|string",
//            "version" => "required|numeric",
//            "requirements.client_version" => "required",
//            "content" => "required|array",
//            "content.titles" => "required|array",
//            "content.titles.attractor" => "required|null",
//            "content.titles.gallery" => "required|exists:galleries,id",
//            "content.titles.idleTimeout" => "required|numeric",
//            "content.titles.title" => "required|string",
//            "content.contents" => "required|array|filled",
//            "content.contents.*" => "",
        ];
    }
}

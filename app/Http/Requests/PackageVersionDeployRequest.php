<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PackageVersionDeployRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user() && $this->user()->can('deploy packages to all kiosks');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'kiosk' => 'required|exists:kiosks,id',
        ];
    }
}

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
        return $this->user()->can('update', $this->route('kiosk'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|unique:kiosks,name,' . $this->route('kiosk')->id,
            'location' => 'sometimes|string|nullable',
            'asset_tag' => 'sometimes|string|nullable|unique:kiosks,asset_tag,' . $this->route('kiosk')->id,
            'manually_set' => 'nullable',
            'assigned_package_version' => 'sometimes|nullable|exists:package_versions,id',
        ];
    }
}

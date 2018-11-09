<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KioskHealthCheckRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'identifier' => 'required|string',
            'client.version' => 'required|string',
            'running_package' => 'array',
            'running_package.name' => 'string|nullable',
            'running_package.version' => 'numeric|nullable',
            'running_package.manually_set' => 'numeric|nullable',
            'logs' => 'array',
            'logs.*' => 'array',
            'logs.*.timestamp' => 'date|required_with:logs.*',
            'logs.*.message' => 'string|required_with:logs.*',
        ];
    }
}

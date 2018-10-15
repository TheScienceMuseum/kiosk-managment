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
            'package.name' => '',
            'package.version' => '',
            'logs' => 'array',
            'logs.*' => 'array',
            'logs.*.timestamp' => 'date|required_with:logs.*',
            'logs.*.line' => 'string|required_with:logs.*',
        ];
    }
}

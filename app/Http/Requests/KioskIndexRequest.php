<?php

namespace App\Http\Requests;

use App\Kiosk;
use Illuminate\Foundation\Http\FormRequest;

class KioskIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('index', Kiosk::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "filter['registered']" => "boolean",
        ];
    }
}

<?php

namespace App\Http\Controllers\Api;


use App\Http\Requests\ApiIndexRequest;

class ApiInformationController extends Controller
{
    public function resources(ApiIndexRequest $request)
    {
        return response()
            ->json(json_decode(file_get_contents(base_path('resources/application-schema.json'))))
            ;
    }
}

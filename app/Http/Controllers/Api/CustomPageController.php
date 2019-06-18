<?php

namespace App\Http\Controllers\Api;

use App\CustomPage;
use App\Http\Resources\CustomPageResource;

class CustomPageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        return CustomPageResource::collection(CustomPage::all());
    }

    /**
     * Display the specified resource.
     *
     * @param  CustomPage  $customPage
     */
    public function show(CustomPage $customPage) : CustomPageResource
    {
        return new CustomPageResource($customPage);
    }
}

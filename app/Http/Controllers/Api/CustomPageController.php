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
     * @param  \App\CustomPage  $customPage
     * @return \Illuminate\Http\Response
     */
    public function show(CustomPage $customPage)
    {
        return new CustomPageResource($customPage);
    }
}

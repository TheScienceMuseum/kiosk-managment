<?php

namespace App\Http\Controllers\Api;

use App\HelpTopic;
use App\Http\Requests\HelpTopicIndexRequest;
use App\Http\Requests\HelpTopicShowRequest;
use App\Http\Requests\HelpTopicUpdateRequest;

class HelpTopicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param HelpTopicIndexRequest $request
     * @return void
     */
    public function index(HelpTopicIndexRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param HelpTopicShowRequest $request
     * @param  \App\HelpTopic $helpTopic
     * @return void
     */
    public function show(HelpTopicShowRequest $request, HelpTopic $helpTopic)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param HelpTopicUpdateRequest $request
     * @param  \App\HelpTopic $helpTopic
     * @return void
     */
    public function update(HelpTopicUpdateRequest $request, HelpTopic $helpTopic)
    {
        //
    }
}

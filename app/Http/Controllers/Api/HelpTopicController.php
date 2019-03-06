<?php

namespace App\Http\Controllers\Api;

use App\HelpTopic;
use App\Http\Requests\HelpTopicForContextRequest;
use App\Http\Requests\HelpTopicIndexRequest;
use App\Http\Requests\HelpTopicShowRequest;
use App\Http\Requests\HelpTopicUpdateRequest;
use App\Http\Resources\HelpTopicResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class HelpTopicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param HelpTopicIndexRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(HelpTopicIndexRequest $request) : AnonymousResourceCollection
    {
        return HelpTopicResource::collection(HelpTopic::all());
    }

    /**
     * Display a HelpTopic given a contextual URL
     *
     * @param HelpTopicForContextRequest $request
     * @return HelpTopicResource
     */
    public function showByContext(HelpTopicForContextRequest $request) : HelpTopicResource
    {
        $helpTopic = HelpTopic::getByRequestUrl($request);

        return new HelpTopicResource($helpTopic);
    }

    /**
     * Display the specified resource.
     *
     * @param HelpTopicShowRequest $request
     * @param  \App\HelpTopic $helpTopic
     * @return HelpTopicResource
     */
    public function show(HelpTopicShowRequest $request, HelpTopic $helpTopic) : HelpTopicResource
    {
        return new HelpTopicResource($helpTopic);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param HelpTopicUpdateRequest $request
     * @param  \App\HelpTopic $helpTopic
     * @return HelpTopicResource
     */
    public function update(HelpTopicUpdateRequest $request, HelpTopic $helpTopic) : HelpTopicResource
    {
        $helpTopic->update([
            'content' => $request->input('content'),
        ]);

        return new HelpTopicResource($helpTopic);
    }
}

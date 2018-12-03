<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\Api\TopicRequest;
use App\Models\Topic;
use App\Transformers\TopicTransformer;

class TopicsController extends Controller
{
    public function store(TopicRequest $request, Topic $topic)
    {
        $topic->fill($request->all());
        $topic->user_id = $this->user()->id;
        $topic->save();

        return $this->response->item($topic, new TopicTransformer())
            ->setStatusCode(201);
    }

    public function show(Topic $topic)
    {
        return $this->response->item($topic, new TopicTransformer());
    }

    public function update(TopicRequest $request, Topic $topic)
    {
        $this->authorize('update', $topic);

        $topic->update($request->all());

        return $this->response->item($topic, new TopicTransformer());
    }

    public function destory(Topic $topic)
    {
        $this->authorize('delete', $topic);

        $topic->delete();

        return $this->response->noContent();
    }
}

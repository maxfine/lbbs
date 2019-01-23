<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\Api\TopicRequest;
use App\Models\Topic;
use App\Transformers\TopicTransformer;
use App\Models\User;

class TopicsController extends Controller
{
    public function index(Request $request, Topic $topic)
    {
        $query = $topic->query();
        if ($category_id = $request->category_id) {
            $query = $query->where('category_id', $category_id);
        }

        switch ($request->order) {
            case 'recent' :
                $query->recent();
                break;
            default :
                $query->recentReplied();
                break;
        }

        $topics = $query->paginate(10);

        return $this->response->paginator($topics, new TopicTransformer());
    }

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

    public function userIndex(User $user)
    {
        $topics = $user->topics()->recent()->paginate(10);

        return $this->response->paginator($topics, new TopicTransformer());
    }
}

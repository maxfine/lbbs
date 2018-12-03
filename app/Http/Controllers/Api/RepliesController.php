<?php

namespace App\Http\Controllers\Api;

use App\Models\Topic;
use Illuminate\Http\Request;
use App\Models\Reply;
use App\Http\Requests\Api\ReplyRequest;
use App\Transformers\ReplyTransformer;
use App\Models\User;

class RepliesController extends Controller
{
    public function store(ReplyRequest $request, Topic $topic)
    {
        $reply = new Reply();
        $reply->user_id = \Auth::guard('api')->id();
        $reply->topic_id = $topic->id;
        $reply->content = $request->get('content');
        $reply->save();

        return $this->response->item($reply, new ReplyTransformer())->setStatusCode(201);
    }

    public function index(Topic $topic, Request $request)
    {
        $replies = $topic->replies()->paginate(10);

        return $this->response->paginator($replies, new ReplyTransformer());
    }

    public function userIndex(User $user)
    {
        $replies = $user->replies()->paginate(10);

        $this->response->paginator($replies, new ReplyTransformer());
    }

    public function destory(Reply $reply)
    {
        $this->authorize('delete', $reply);

        $reply->delete();

        return $this->response->noContent();
    }
}

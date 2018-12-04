<?php

namespace App\Observers;

use App\Models\Reply;
use App\Notifications\TopicReplied;

class ReplyObserver
{
    public function created(Reply $reply)
    {
        $topic = $reply->topic;
        $topic->increment('reply_count');

        if($topic->user_id != $reply->user_id) {
            $topic->user->notify(new TopicReplied($reply));
        }
    }
}
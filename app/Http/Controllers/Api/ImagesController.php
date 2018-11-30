<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\Api\ImageRequest;
use App\Models\Image;
use App\Handlers\ImageUploadHandler;
use App\Transformers\ImageTransformer;

class ImagesController extends Controller
{
    public function store(ImageRequest $request, ImageUploadHandler $handler)
    {
        $type = $request->type;
        $user = \Auth::guard('api')->user();
        $size = $type == 'avatar' ? 362 : 1024;
        $result = $handler->save($request->image, $type, $user->id, $size);

        $image = new Image();
        $image->path = $result['path'];
        $image->user_id = $user->id;
        $image->type = $type;
        $image->save();

        return $this->response->item($image, new ImageTransformer());
    }
}

<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\Api\UserRequest;
use App\Models\User;
use App\Transformers\UserTransformer;
use App\Models\Image;

class UsersController extends Controller
{
    public function store(UserRequest $request)
    {
        $verificationKey = $request->verification_key;
        $verificationCode = $request->verification_code;

        $verifyData = \Cache::get($verificationKey);
        if(!$verifyData) {
            $this->response->error('验证码已失效', 422);
        }

        if(!hash_equals($verificationCode, (string)$verifyData['code'])) {
            return $this->response->errorUnauthorized('验证码错误');
        }

        $user = User::create([
            'name' => $request->name,
            'phone' => $verifyData['phone'],
            'password' => bcrypt($request->password),
        ]);

        \Cache::forget($verificationKey);

        return $this->response->created();
    }

    public function me()
    {
        $user = \Auth::guard('api')->user();

        return $this->response->item($user, new UserTransformer());
    }

    public function update(UserRequest $request)
    {
        $user = $this->user();

        $attributes = $request->only(['name', 'email', 'introduction', 'registration_id']);

        if ($request->avatar_image_id) {
            $image = Image::find($request->avatar_image_id);

            $attributes['avatar'] = $image->path;
        }
        $user->update($attributes);

        return $this->response->item($user, new UserTransformer());
    }
}

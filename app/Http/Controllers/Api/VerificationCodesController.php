<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\Api\VerificationCodeRequest;
use Overtrue\EasySms\EasySms;
use Illuminate\Support\Str;

class VerificationCodesController extends Controller
{
    public function store(VerificationCodeRequest $request, EasySms $easySms)
    {
        $captchaData = \Cache::get($request->captcha_key);
        if(empty($captchaData)) {
            $this->response->error('图片验证码已失效', 422);
        }
        if(!hash_equals(Str::lower($request->get('captcha_code')),  Str::lower($captchaData['code']))) {
            \Cache::forget($request->captcha_key);
            $this->response->errorUnauthorized('图片验证码不正确');
        }

        $phone = $captchaData['phone'];
        if(!app()->environment('production')) {
            $code = '123456';
        } else {
            try {
                $code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);
                $message = '【larabbs】你的验证码是:'. $code;
                $easySms->send($phone, ['content' => $message]);
            } catch (\Exception $e) {
                return $this->response->errorInternal($e->getMessage() ?: '短信发送异常');
            }
        }

        $key = 'verificationCode_'.str_random(15);
        $expiredAt = now()->addMinutes(10);
        \Cache::forget($request->captcha_key);
        \Cache::put($key, ['code' => $code, 'phone' => $phone], $expiredAt);
        $result = ['key' => $key, 'expiredAt' => $expiredAt->toDateTimeString()];

        return $this->response->array($result)->setStatusCode(201);
    }
}

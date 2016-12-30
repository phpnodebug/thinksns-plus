<?php

namespace App\Http\Controllers\APIs\V1;

use App\Http\Controllers\Controller;
use App\Exceptions\MessageResponseBody;
use App\Handler\SendMessage;
use App\Models\VerifyCode;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * 发送注册验证码.
     *
     * @param Request $request 请求对象
     *
     * @return Response 返回对象
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    public function sendPhoneCode(Request $request)
    {
        $vaildSecond = 60;
        $phone = $request->input('phone');
        $verify = VerifyCode::byAccount($phone)->byValid($vaildSecond)->orderByDesc()->first();

        if ($verify) {
            return app(MessageResponseBody::class, [
                'code' => 1008,
                'data' => $verify->makeSurplusSecond($vaildSecond),
            ]);
        }

        $verify = new VerifyCode();
        $verify->account = $phone;
        $verify->makeVerifyCode();
        $verify->save();

        return app(SendMessage::class, [$verify, 'type' => 'phone'])->send();
    }
}
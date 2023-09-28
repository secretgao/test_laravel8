<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
/**
 * @OA\Info(title="My First API", version="0.1")
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * 成功返回.
     *
     * @param array  $data
     * @param string $msg
     *
     * @return mixed
     */
    public function success($data, $msg = "OK")
    {
        $result = [
            "code" => 200,
            "msg" => $msg,
            "data" => $data,
        ];

        return response()->json($result, 200);
    }

    /**
     * 失败返回.
     *
     * @param string $code
     * @param array  $data
     * @param string $msg
     *
     * @return mixed
     */
    public function error($code = "422", $data = [], $msg = "fail")
    {
        $result = [
            "code" => $code,
            "msg" => $msg,
            "data" => $data,
        ];

        return response()->json($result, 200);
    }
}

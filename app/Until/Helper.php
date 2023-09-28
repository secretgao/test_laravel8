<?php
namespace App\Until;

class Helper {

    /**
     * 成功返回.
     *
     * @param array  $data
     * @param string $msg
     *
     * @return mixed
     */
    public static  function success($data, $msg = "OK")
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
    public static function error($code = "422", $data = [], $msg = "fail")
    {
        $result = [
            "code" => $code,
            "msg" => $msg,
            "data" => $data,
        ];

        return response()->json($result, 200);
    }
}

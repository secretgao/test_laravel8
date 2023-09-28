<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
/**
 * @OAGet(
 *     path="/api/user",
 *     @OAResponse(response="200", description="Display a listing of projects.")
 * )
 */
class UserController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"对外服务"},
     *     summary="用户登陆",
     *     description="用户登陆",
     *     operationId="UserController/login",
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="application/json",
     *           example={
     *              "name": "seven","password":"123456"
     *           }
     *       )
     *   ),
     *     @OA\Response(
     *         response=422,
     *         description="请求错误示例",
     *        @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="code", type="integer", description="422"),
     *                 @OA\Property(property="msg", type="string", description="消息"),
     *                 @OA\Property(property="data", type="object", description="数据"),
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="请求成功示例",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="code", type="integer", description="状态码"),
     *                 @OA\Property(property="msg", type="string", description="消息"),
     *                 @OA\Property(property="data", type="object",
     *                       @OA\Property(property="token", type="string",description="token")),
     *             ),
     *         )
     *     ),
     * )
     */
    public function login(Request $request){

        //自定义错误信息
        $message =[
            'name.required' =>'请填写用户名!',
            'password.required' => '请填写密码!',
        ];
        //参数校验
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'password' => 'required',
        ],$message);
        if ($validator->fails()) {
            return $this->error(422,[],$validator->errors()->first());
        }

        // 获取通过验证的数据
        $validated = $validator->validated();

        $user = User::query()->where(['name'=>$validated['name']])->first();

        if (empty($user)){
            return $this->error(422,[], '用户不存在');
        }
        if (! Hash::check($validated['password'], $user->password)) {
            return $this->error(422,[], '密码错误');
        }
        $token = $user->createToken($user->password)->plainTextToken;
        $result = Redis::set('Bearer '.$token, json_encode($user->toArray()),600);
        return $this->success(['token'=>$token],'登陆成功');

    }
    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"对外服务"},
     *     summary="用户注册",
     *     description="用户注册",
     *     operationId="UserController/register",
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="application/json",
     *           example={
     *              "name": "seven", "email": "seven@seven.com","password":"123456"
     *           }
     *       )
     *   ),
     *     @OA\Response(
     *         response=422,
     *         description="请求错误示例",
     *        @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="code", type="integer", description="422"),
     *                 @OA\Property(property="msg", type="string", description="消息"),
     *                 @OA\Property(property="data", type="object", description="数据"),
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="请求成功示例",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="code", type="integer", description="状态码"),
     *                 @OA\Property(property="msg", type="string", description="消息"),
     *                 @OA\Property(property="data", type="object", description="数据"),
     *             ),
     *         )
     *     ),
     * )
     */
    public function register(Request $request){
        //自定义错误信息
        $message =[
            'name.required' =>'请填写用户名!',
            'email.required' => '请填写邮箱!',
            'password.required' => '请填写密码!',
        ];
        //参数校验
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ],$message);
        if ($validator->fails()) {
           return $this->error(422,[],$validator->errors()->first());
        }

        // 获取通过验证的数据
        $validated = $validator->validated();
        $result =   \App\Models\User::factory()->create($validated)->getAttribute('id');
        if ($result){
            return $this->success([],'注册成功');
        }
        return $this->error(422,[],'注册失败 ');
    }


    /**
     * @OA\Get(
     *     path="/api/user_info",
     *     tags={"对外服务"},
     *     summary="用户信息",
     *     description="用户信息",
     *     operationId="UserController/getUserById",
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="application/json",
     *           example={
     *              "id": "9"
     *           }
     *       )
     *   ),
     *     @OA\Response(
     *         response=422,
     *         description="请求错误示例",
     *        @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="code", type="integer", description="422"),
     *                 @OA\Property(property="msg", type="string", description="消息"),
     *                 @OA\Property(property="data", type="object", description="数据"),
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="请求成功示例",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="code", type="integer", description="状态码"),
     *                 @OA\Property(property="msg", type="string", description="消息"),
     *                 @OA\Property(property="data", type="object",
     *                       @OA\Property(property="id", type="integer",description="Id"),
     *                       @OA\Property(property="name", type="string",description="账号"),
     *                       @OA\Property(property="email", type="string",description="email")
     * ),
     *             ),
     *         )
     *     ),
     * )
     */
    public function getUserById(Request $request){

        $id = $request->get('id');
        if (empty($id)){
            return $this->error(422,[],'请传入参数');
        }

        $user = User::query()->where(['id'=>$id])->first();
        if  (empty($user)){
            return $this->error(422,[],'数据不存在');
        }
        return $this->success($user,'成功');

    }


    /**
     * @OA\Put(
     *     path="/api/user_update",
     *     tags={"对外服务"},
     *     summary="更新用户邮箱",
     *     description="更新用户邮箱",
     *     operationId="UserController/UpdateUserEmailById",
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="application/json",
     *           example={
     *              "id": "9","email":"new@qq.com"
     *           }
     *       )
     *   ),
     *     @OA\Response(
     *         response=422,
     *         description="请求错误示例",
     *        @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="code", type="integer", description="422"),
     *                 @OA\Property(property="msg", type="string", description="消息"),
     *                 @OA\Property(property="data", type="object", description="数据"),
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="请求成功示例",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="code", type="integer", description="状态码"),
     *                 @OA\Property(property="msg", type="string", description="消息"),
     *                @OA\Property(property="data", type="object", description="数据"),
     *            )
     *         )
     *     ),
     * )
     */
    public function UpdateUserEmailById(Request $request){

        //自定义错误信息
        $message =[
            'id.required' =>'请传入参数id',
            'email.required' => '请填写邮箱!',
        ];
        //参数校验
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'email' => 'required',
        ],$message);
        if ($validator->fails()) {
            return $this->error(422,[],$validator->errors()->first());
        }
        $validated = $validator->validated();
        $user = User::query()->where(['id'=>$validated['id']])->first();
        if  (empty($user)){
            return $this->error(422,[],'数据不存在');
        }
        unset($validated['id']);
        if ($user->update($validated)){
            return $this->success([],'成功');
        }
        return $this->error(422,'失败');
    }

    /**
     * @OA\Delete(
     *     path="/api/user_del",
     *     tags={"对外服务"},
     *     summary="删除用户",
     *     description="删除用户",
     *     operationId="UserController/deleteUserById",
     *   @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="application/json",
     *           example={
     *              "id": "9"
     *           }
     *       )
     *   ),
     *     @OA\Response(
     *         response=422,
     *         description="请求错误示例",
     *        @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="code", type="integer", description="422"),
     *                 @OA\Property(property="msg", type="string", description="消息"),
     *                 @OA\Property(property="data", type="object", description="数据"),
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="请求成功示例",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="code", type="integer", description="状态码"),
     *                 @OA\Property(property="msg", type="string", description="消息"),
     *                @OA\Property(property="data", type="object", description="数据"),
     *            )
     *         )
     *     ),
     * )
     */
    public function deleteUserById(Request $request){
        
        $id = $request->get('id',0);
        if (empty($id)){
            return $this->error(422,[],'请传入参数id');
        }

        $user = User::query()->where(['id'=>$id])->first();
        if  (empty($user)){
            return $this->error(422,[],'数据不存在');
        }

        if ($user->delete()){
            return $this->success([],'成功');
        }
        return $this->error(422,'失败');
    }
}

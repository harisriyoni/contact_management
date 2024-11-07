<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserResouce;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function register(UserRegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        if (User::query()->where('username', $data['username'])->count() == 1) {
            //ada di database?
            // jika ada data username yang sama akan menampilkan errors response exception
            throw new HttpResponseException(response([
                "errors" => [
                    "username" => "username already registered",
                ],
            ], 400));
            //erorr exception ini untuk menangani error ketika ada username yang sama yang ini User::query()->where('username', $data['username'])->count() == 1
        }
        $user = new User($data);
        $user->password = Hash::make($data['password']);
        $user->save();
        return (new UserResouce($user))->response()->setStatusCode(201);
    }
    //karna kita tidak perlu mengubah status code maka ya pake kembalian yang mengembalikan userResource aja, dan defalut 200
    public function login(UserLoginRequest $request): UserResouce
    {
        $data = $request->validated();
        $user = User::where('username', $data['username'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => "username or password wrong",
                ],
            ], 401));
        }
        $user->token = Str::uuid()->toString();
        $user->save();
        return new UserResouce($user);
    }
}

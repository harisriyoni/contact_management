<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResouce;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
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

    public function get(Request $request): UserResouce
    {
        //dapetin user login saat ini.
        $user = Auth::user();
        return new UserResouce($user);
    }
    public function update(UserUpdateRequest $request): UserResouce
    {
        $data = $request->validated();
        $user = Auth::user();

        if (isset($data['name'])) {
            $user->name = $data['name'];
        }

        if (isset($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();
        return new UserResouce($user);
    }
    public function logout(Request $request):JsonResponse{
        $user = Auth::user();
        $user->token = null;
        $user->save();
        return response()->json([
            "data" => true,
        ])->setStatusCode(200);
    }
}

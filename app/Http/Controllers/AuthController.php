<?php

namespace App\Http\Controllers;

use App\Http\Requests\userRegister;
use App\Http\Requests\UserRequest;
use App\Http\Resources\userResource;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{

  public function test(userRegister $request)
{
   
    $user = User::create([
        'name' => $request['name'],
        'email' => $request['email'],
        'role'=>'admin',
        'password' => bcrypt($request['password']),
    ]);

    $token = $user->createToken('myapptoken')->plainTextToken;

    return response([
        'user' => $user,
        'token' => $token,
    ], 201);
}

    public function login(userRequest $request){

        $user=User::where('email',$request['email'])->first();

        if(!$user || !Hash::check($request['password'],$user->password)){
            return response(["msg"=>"Incorrect email or password"]);
        }
        $token=$user->createToken('myapptoken')->plainTextToken;
        $response=['user'=>new userResource($user),'token'=>$token];
          return response($response);   
    }

     public function logout(Request $request){
        $accessToken=$request->bearerToken();
        $token=PersonalAccessToken::findToken($accessToken);
        $token->delete();
        return response(['msg'=>"Logged Out"]);
    }

}

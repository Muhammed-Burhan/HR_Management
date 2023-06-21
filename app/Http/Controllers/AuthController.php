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

  public function register(userRegister $request)
{
   
    $user = User::create([
        'name' => $request['name'],
        'email' => $request['email'],
        'role'=>'super_admin',
        'password' => bcrypt($request['password']),
    ]);

    $token = $user->createToken('myapptoken', ['expires_in' => 60 * 60 * 24 * 3])->plainTextToken;

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
        $token = $user->createToken('myapptoken', ['expires_in' => 60 * 60 * 24* 3])->plainTextToken;
        $response=['user'=>new userResource($user),'token'=>$token];
          return response($response);   
    }

     public function logout(Request $request){
        $accessToken=$request->bearerToken();
        if($accessToken){
            $token=PersonalAccessToken::findToken($accessToken);
             $token->delete();
             return response(['msg'=>"Logged Out"]);
        }
    
    }

       
}

<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Bridge\User;
use Laravel\Socialite\Facades\Socialite;


class AuthController extends Controller
{
   

    //Register
    public function register(Request $request){
      $fields = $request -> validate([
          'fullname'=> 'required|string',
          'email'=>'required|string|unique:users,email',
          'password'=>'required|string|confirmed'
      ]);
      $user = Users::create([
        'fullname'=>$fields['fullname'],
        'email'=>$fields['email'],
        'password'=> bcrypt($fields['password']),
      ]);

      $token = $user->createToken('Token Name')->accessToken;

      $response = [
          'user'=> $user,
          'token'=> $token
      ];
      return response($response);
    }


    //Login
    
    public function login(Request $request){
        $fields = $request -> validate([
            
            'email'=>'required|string',
            'password'=>'required|string'
        ]);
        $credentials = Users::where('email', $fields['email'])->first();
        if(!$credentials || !Hash::check($fields['password'],$credentials->password)){
            return response(['Message' => 'Incorrect Credentials']);
        }  
        $token = $credentials->createToken('Token Name')->accessToken;
  
        $response = [
            'user'=> $credentials,
            'token'=> $token
        ];
        return response($response);
      }


    //LOGOUT

    public function logout(){
        auth()->user()->token()->delete();
       $response = [
           'user'=>auth()->user(),
           'token'=> 'Deleted',
           'message'=>'Logged out'
       ];
        return response($response);

    }


    //Socialite
    public function googlecall(){
        return Socialite::driver('google')->stateless()->redirect();
    }
    public function googlecallback(){
        $user = Socialite::driver('google')->stateless()->user();
        return response()->json($user);
    }
  
}

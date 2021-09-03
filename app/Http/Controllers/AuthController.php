<?php

namespace App\Http\Controllers;
use Illuminate\Auth\Events\Registered;
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
      $input = $request -> validate([
          'fullname'=> 'required|regex:/^[^<>"]+$/|string',
          'email'=>'required|regex:/^[^<>"]+$/|string|email|unique:users',
          'password'=>'required|regex:/^[^<>"]+$/|string|confirmed'
      ]);
      
      $user = Users::create([
        'fullname'=>$input['fullname'],
        'email'=>$input['email'],
        'password'=> bcrypt($input['password']),
      ]);
      
    $token = $user->createToken('Token Name')->accessToken;

      $response = [
          'user'=> $user,
          'token'=> $token
      ];
      return response()->json($response);
    }


    //Login
    
    public function login(Request $request){
        $input = $request -> validate([
            'email'=>'required|regex:/^[^<>"]+$/|string',
            'password'=>'required|regex:/^[^<>"]+$/|string'
        ]);
            if(Auth::attempt(['email' => $input['email'], 'password' => $input['password']])){
                return response([
                    'message'=>"successful",
                    'user'=>auth()->user(),
                    'token'=>auth()->user()->createToken("Token Name")->accessToken
                
                ]);
            }else{
                return response(['message' => 'Incorrect Credentials']);
            }
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


    //Emailcallback
   
  
}

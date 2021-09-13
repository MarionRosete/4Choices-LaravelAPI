<?php

namespace App\Http\Controllers;
use Illuminate\Auth\Events\Registered;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Passport\Bridge\User;
use Laravel\Socialite\Facades\Socialite;
use App\Mail\RegisterUser;
use App\Mail\MailForgetPassword;
use App\Models\ForgetPassword;
use App\Models\UserRegistrationCodes;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
class AuthController extends Controller
{
   
   /**
    * 
    */
    
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
      
      if($user){
            $code=UserRegistrationCodes::create([
                "user_id"=>$user->id,
                "code"=> Str::random(10)
            ]);
            

            Mail::to($user->email)->send(new RegisterUser($user,$code['code']));
            return response([
                "success"=>true,
                "message"=>"An Code was sent to your Email Address, Please enter it here",
                "code"=>$code,
               
            ]);
      }
      return response(["success"=>false,"message"=>"Email Registration not sent"]);
    }

     /**
      * 
      */

     public function verifyemail($code){
            $validcode = UserRegistrationCodes::where(['code'=>$code])->first();
           
        if($validcode){
            $user_fk = $validcode->user_id;
            $user = Users::where(['id'=>$user_fk])->first();
            $user->email_verified_at = Carbon::now();
            $user->save();
           
            return response(["success"=>true,"code"=>$validcode, "user"=>$user,  "token"=>$user->createToken("Token Name")->accessToken]);
        }else{
            return response(["success"=>false,]);
        }
    }
    public function createForgotPassword(Request $request){
        $email = $request -> validate([
            'email'=>'required|regex:/^[^<>"]+$/|string|email|',
        ]);
        $user = Users::where(['email'=>$email])->first();
        if($user){
            $code=ForgetPassword::create([
                "email"=>$user->email,
                "code"=> Str::random(10)
            ]);
            Mail::to($user->email)->send(new MailForgetPassword($user,$code['code']));
            return response([
                "success"=>true,
                "message"=>"We've sent an code to your email address.",
                "code"=>$code 
            ]);
          
           
        }
        return response(["succcess"=>false, "message"=>"Email not Found",]);
      }
    /**
     * 
     */
    public function updatepassword(Request $request, $code){
        $validcode = ForgetPassword::where(['code'=>$code])->first();
        if($validcode){
            $input = $request -> validate([
                'password'=>'required|regex:/^[^<>"]+$/|string|confirmed'
            ]);
            $user = Users::where('email', $validcode->email)->update(["password"=>bcrypt($input['password'])]);
          
              return response([
                "status"=>true,
                "message"=>"Updated Password",
                "user"=>$validcode,

                "updated"=>$user
              ]);

        }
        return response([
            "status"=>false,
            "message"=>"invalid "
        ]);
    }

    /**
     * 
     */

    public function login(Request $request){
        $input = $request -> validate([
            'email'=>'required|regex:/^[^<>"]+$/|string',
            'password'=>'required|regex:/^[^<>"]+$/|string'
        ]);
            if(Auth::attempt(['email' => $input['email'], 'password' => $input['password']])){
                return response([
                    "success"=>true,
                    "message"=>"Authenticated User",
                    "user"=>auth()->user(),
                    "token"=>auth()->user()->createToken("Token Name")->accessToken
                
                ]);
            }else{
                return response(['message' => 'Incorrect Credentials']);
            }
    }

    /**
     * 
     */

    public function user(){
        
        return response([
            "success"=>true,
            "message"=>"authentic",
            "user"=>auth()->user()
        ]);

    }
    /**
     * 
     */
    public function logout(){
        auth()->user()->token()->delete();
       $response = [
           'user'=>auth()->user(),
           'token'=> 'Deleted',
           'message'=>'Logged out'
       ];
        return response($response);

    }
    /**
     * 
     */
    public function googlecall(){
        return Socialite::driver('google')->stateless()->redirect();
    }
    public function googlecallback(){
        $user = Socialite::driver('google')->stateless()->user();
        return response()->json($user);
    }
    /**
     * 
     */
  
}

<?php
use App\Http\Controllers\ExamController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//exposed routes
Route::post('/login', [AuthController::class,'login']);
Route::post('/register', [AuthController::class,'register']);
Route::post('/dashboard/qa/{code}', [ExamController::class,'qa']);
//email routes
Route::get('verifyemail/{code}',[AuthController::class,'verifyemail']);
Route::post('/forgotpassword',[AuthController::class,'createForgotPassword']);
Route::post('/updatepassword/{code}',[AuthController::class,'updatepassword']);



//SOCIALITE ROUTES
Route::get('/login/google-redirect', [AuthController::class,'googlecall']);
Route::post('/login/googlecallback',[AuthController::class,'googlecallback']);

//AUTHENTICATED ROUTES
Route::group(['middleware'=>['auth:api', 'verified']],function(){
    Route::post('/dashboard/logout', [AuthController::class, 'logout']);
    Route::post('/dashboard/createExam', [ExamController::class,'createExam']);
    Route::get('/dashboard/user', [AuthController::class,'user']);
    Route::get('/dashboard/exam', [ExamController::class,'myexam']);
    Route::post('/dashboard/activate', [ExamController::class,'activate']);
    
});




Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

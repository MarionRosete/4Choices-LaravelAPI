<?php
use App\Http\Controllers\ExamController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



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



//CORS PROTECTED

//UNAUTHENTICATED ROUTES
Route::post('/login', [AuthController::class,'login']);
Route::post('/register', [AuthController::class,'register']);

//SOCIALITE ROUTES
Route::get('/login/google-redirect', [AuthController::class,'googlecall']);
Route::get('/login/googlecallback',[AuthController::class,'googlecallback']);

//AUTHENTICATED ROUTES
Route::group(['middleware'=>['auth:api']],function(){
    Route::post('/dashboard/logout', [AuthController::class, 'logout']);
    Route::post('/dashboard/createExam', [ExamController::class,'createExam']);
    
});




Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\GroupAdminController;
use App\Http\Controllers\BirthdayController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SmsController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/login', [AuthController::class, 'login']);

Route::any('/aws-sms-test', [SmsController::class, 'twilio']);



Route::get('/sms', [SmsController::class, 'aws']);
Route::get('/crone-job-test', [BirthdayController::class, 'dailyBirthday']);
// Route::get('/test', [NotificationController::class, 'twilioSms']);

Route::group(['middleware' => 'auth:sanctum',], function (){
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::group(['middleware' => 'auth:sanctum',], function (){
    Route::get('/admin', [AdminController::class, 'index']);
    Route::post('/admin', [AdminController::class, 'store']);
    Route::get('/admin/{id}', [AdminController::class, 'show']);
    Route::put('/admin/{id}', [AdminController::class, 'update']);
    Route::delete('/admin/{id}', [AdminController::class, 'destroy']);

    Route::get('/birthdays-count', [BirthdayController::class, 'birthdayscount']);
    Route::get('/users-count', [StatisticsController::class, 'profileCount']);
    Route::post('/create-admin', [SuperAdminController::class, 'createAdmin']);
    Route::get('/admin-count', [StatisticsController::class, 'count']);
    Route::get('/birthdays', [BirthdayController::class, 'birthdays']);
    Route::get('/search', [SearchController::class, 'search']);

});

Route::group(['middleware' => 'auth:sanctum',], function (){

    Route::apiResource('/group', GroupController::class);

    Route::get('/group-count', [GroupController::class, 'count']);
    Route::get('/admin-profile', [GroupAdminController::class, 'admin']);
    Route::post('/add-member', [GroupAdminController::class, 'addMember']);
    Route::get('/admin-group', [GroupAdminController::class, 'getMyGroupName']);
});

Route::group(['middleware' => 'auth:sanctum',], function (){

    Route::get('/user/{id}', [UserController::class, 'show']);
    Route::put('/user/{id}', [UserController::class, 'update']);
    Route::delete('/user/{id}', [UserController::class, 'destroy']);

    Route::apiResource('/user', ProfileController::class);//->except('show', 'update', 'delete');

    Route::get('/add-member', [GroupAdminController::class, 'addMember']);
    Route::get('/admin-groups', [GroupAdminController::class, 'myGroups']);
    Route::get('/admin-group', [GroupAdminController::class, 'myGroup']);
    Route::get('/birthday', [BirthdayController::class, 'birthday']);
    Route::get('/user-count', [StatisticsController::class, 'membersCount']);
    Route::get('/birthday-count', [BirthdayController::class, 'birthdaycount']);
});

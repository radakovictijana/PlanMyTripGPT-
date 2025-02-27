<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TravelPlanController; 
use App\Http\Controllers\ActivityController; 
use App\Http\Controllers\DailyPlanController; 
use App\Http\Controllers\Auth\LoginController; 
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\ImageController;

 

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/upload', function () {
    return view('upload'); 
});

Route::post('/upload', [ImageController::class, 'upload'])->name('image.upload');


Route::post('/gpt', [DailyPlanController::class,'generateTravelPlan']); 
Route::post('/gpt', [DailyPlanController::class,'generateTravelPlan']); 

Route::get('/users', [UserController::class,'index']); //uradjeno
Route::get('/users/{id}', [UserController::class,'show']); //uradjeno
Route::get('/users/{id}/travels', [UserController::class,'indexUserTravel']); //uradjeno


//Route::resource('travels',TravelPlanController::class)->only('show','index');
Route::post('register',[LoginController::class,'register']);
Route::get('/travels/search', [TravelPlanController::class, 'search']);
Route::get('/travels', [TravelPlanController::class, 'index']);
Route::get('/travels/{id}', [TravelPlanController::class, 'show']);
Route::resource('activities',ActivityController::class)->only('show','index');
Route::post('logout',[LoginController::class,'logout']);


Route::post('login',[LoginController::class,'login']); //uradjeno
Route::group(['middleware'=>['auth:sanctum']],function (){
    Route::get('/profile', function (Request $request){
        return auth()->user();
    });
    Route::get('travels/{id}/activity',[TravelPlanController::class,'showPlans']);
    Route::delete('travels/{id}',[TravelPlanController::class,'destroy']);
    Route::put('travels/{id}',[TravelPlanController::class,'update']);
    Route::post('travels',[TravelPlanController::class,'store']);
    Route::resource('activities',ActivityController::class)->only('update','store');
    Route::post('travels',[TravelPlanController::class,'store']);
    Route::post('/gpt', [DailyPlanController::class,'generateTravelPlan']); //obrisi
    //Route::post('/gpt', [DailyPlanController::class,'generateTravelPlan']); 
    Route::put('/regenerateDay', [DailyPlanController::class,'regenerateDay']); 




});
Route::group(['middleware' => ['auth:sanctum', 'isAdmin']],function (){
    
    //Route::post('activities',[ActivityController::class,'store']); //uradjeno
    //Route::put('/activities/{id}',[ActivityController::class,'update']);  //uradjeno
    //Route::resource('travels',TravelPlanController::class)->only('destroy','store','update');//vratiti se na update
    //Route::delete('travels/{id}',[TravelPlanController::class,'destroy']);
    Route::put('travels/{id}',[TravelPlanController::class,'update']);
    //Route::post('travels',[TravelPlanController::class,'store']);
    Route::resource('activities',ActivityController::class)->only('update','store');



});


Route::get('/plans',[DailyPlanController::class,'index']); //prikazati na postmanu
Route::get('/plans/{id}',[DailyPlanController::class,'show']);
Route::post('/plans',[DailyPlanController::class,'store']);
Route::put('/plans/{id}',[DailyPlanController::class,'update']);
Route::delete('/plans/{id}',[DailyPlanController::class,'destroy']);


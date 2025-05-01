<?php

use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\API\AuthController;
//use App\Http\Controllers\API\UserSessionController;
use App\Http\Controllers\API\SessionController;
use App\Http\Controllers\API\ProfileController;
use Illuminate\Http\Request;
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
   /* Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api');
    Route::post('/profile', [AuthController::class, 'profile'])->middleware('auth:api');
    Route::resource('user-sessions',UserSessionController::class)->middleware('auth:api');*/ 
});

Route::get('/user', function (Request $request) { return $request->user();
})->middleware('auth: sanctum');
/*Route::post('/upload', function (Request $request) {
    if ($request->hasFile('file')) {
      $file = $request->file('file');
      $path = $file->store('uploads', 'public');
      $url = url(Storage::url($path)); 
        return response()->json(['url' => $url], 200);

    }
   //return response()->json(['error' => 'No file uploaded'], 400);
});*/

Route::post('/upload', [SessionController::class, 'updateSession']);
Route::post('/startSession', [SessionController::class, 'startSession']);
Route::post('/create-profile', [ProfileController::class, 'userProfile']);

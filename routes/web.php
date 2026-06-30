<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/email-template', function () {
    return view('email.sendemailpractitioner'); // create a basic test.blade.php
});
Route::get('/', function () {
    return Redirect::to(route('login'));
});
Route::get('/send-invitation/{user_id}', [SessionController::class, 'sendInvitation'])->name('send.invitation');
Route::post('/send-invitation', [SessionController::class, 'sendInvitationEmail'])->name('sendinvitation.email');

Route::get('/start-session/{user_id}/{session_id?}',[SessionController::class,'startSession'])->name('start-session');
Route::get('/end-session/{user_id}/{session_id}/{is_complete?}', [SessionController::class, 'endSession'])->name('end.session');
//Route::post('/update-recording', [SessionController::class, 'updateRecording'])->name('session.recording');

// Nova profile and password change routes
Route::middleware('auth')->group(function () {
    // Standard password change
    Route::get('/change-password', [ProfileController::class, 'changePassword'])->name('password.change');
    Route::post('/change-password', [ProfileController::class, 'updatePassword'])->name('password.update.custom');
    
    // Nova style pages
    Route::get('/nova/profile', [ProfileController::class, 'novaProfile'])->name('profile.nova');
    Route::post('/nova/profile', [ProfileController::class, 'updateNovaProfile'])->name('profile.update.nova');
    Route::get('/nova/change-password', [ProfileController::class, 'novaChangePassword'])->name('password.change.nova');
    Route::post('/nova/change-password', [ProfileController::class, 'updatePassword'])->name('password.update.nova');
});

//Route::get('/created-session/{user_id}/{session_id}',[SessionController::class,'createdSession'])->name('created.session');

//Route::get('/question', [QuestionController::class, 'index']);
// Route::get('/question', [QuestionController::class, 'index']);
/*Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');*/ 

Route::get('/password-protected', [ProfileController::class, 'passwordCheck']);

Route::post('/password-protected', [ProfileController::class, 'passwordSubmit']);


require __DIR__ . '/auth.php';

<?php

use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


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

Route::get('/', function () {
    return view('welcome');
});


// Route::post('/messages/send', [MessageController::class, 'sendMessage'])->name('messages.send');
Route::post('/sendMessage', [MessageController::class, 'sentMessage']);
Route::get('/messages/{receiver_id}', [MessageController::class, 'getMessages'])->name('messages.get');
Route::post('/unreadMsgHandler', [MessageController::class, 'sendMessage'])->name('unreadMsgHandler');
Route::post('/resource_id', [MessageController::class, 'sendMessage'])->name('resource_id');
 

Route::get('/dashboard/{receiver_id}', [MessageController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

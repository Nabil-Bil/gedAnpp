<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DirectionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::inertia('/', 'Auth/Login');

Route::middleware('auth')->group(function () {
    Route::prefix('dashboard')->group(function(){
        Route::get('/', [AdminController::class, 'index'])->name('dashboard.home');
        Route::get('/register',[AdminController::class,'registerView'])->name("register");
        Route::post("/directions/destroy",[DirectionController::class,"destroy"]);
        Route::resource("/directions",DirectionController::class,[
            'only'=>['index','store','update']
        ]);
        Route::post("/users/destroy",[UserController::class,"destroy"]);
        Route::resource("/users",UserController::class,[
            'only'=>['index','store','update']
        ]);
    });
});

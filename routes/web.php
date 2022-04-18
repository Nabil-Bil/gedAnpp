<?php

use App\Http\Controllers\AccessController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DirectionController;
use App\Http\Controllers\DosageController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\MedicationController;
use App\Http\Controllers\PharmaceuticalEstablishmentController;
use App\Http\Controllers\PresentationController;

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
    Route::prefix('dashboard')->group(function () {
        Route::get('/', [AccessController::class, 'index'])->name('dashboard.home');

        Route::middleware(['admin'])->group(function () {
            Route::post("/directions/destroy", [DirectionController::class, "destroy"]);
            Route::resource("/directions", DirectionController::class, [
                'only' => ['index', 'store', 'update']
            ]);

            Route::post("/users/destroy", [UserController::class, "destroy"]);
            Route::resource("/users", UserController::class, [
                'only' => ['index', 'store', 'update', 'create']
            ]);



            Route::post("/pharmaceuticalEstablishment/destroy", [PharmaceuticalEstablishmentController::class, "destroy"]);
            Route::resource('/pharmaceuticalEstablishment', PharmaceuticalEstablishmentController::class, [
                'only' => ['index', 'store', 'update', 'create']
            ]);
            Route::post("/medication/destroy", [MedicationController::class, "destroy"]);
            Route::resource('medication', MedicationController::class,["only"=>['index', 'store', 'update', 'create']]);

            Route::post("/form/destroy", [FormController::class, "destroy"]);
            Route::resource('/form', FormController::class, [
                'only' => ['store', 'update']
            ]);

            Route::post("/dosage/destroy", [DosageController::class, "destroy"]);
            Route::resource('/dosage', DosageController::class, [
                'only' => ['store', 'update']
            ]);

            Route::post("/presentation/destroy", [PresentationController::class, "destroy"]);
            Route::resource('/presentation', PresentationController::class, [
                'only' => ['store', 'update',]
            ]);
        });
    });
});

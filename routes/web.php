<?php

use Inertia\Inertia;
use App\Models\Document;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DciController;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\FormController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AccessController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DosageController;
use App\Http\Controllers\DirectionController;
use App\Http\Controllers\MedicationController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\PresentationController;
use App\Http\Controllers\TechnicalFileController;
use App\Http\Controllers\ClassificationController;
use App\Http\Controllers\CommentaryController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\PharmaceuticalEstablishmentController;

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

require_once __DIR__ . '/fortify.php';


Route::middleware('auth')->group(function () {
    Route::prefix('dashboard')->group(function () {
        Route::get('/', [AccessController::class, 'index'])->name('dashboard.home');
        Route::put('/user/editpassword', [UserController::class, 'editPassword']);
        Route::put('/user/profile', [UserController::class, 'editProfileData']);
        Route::patch('/user/deleteprofilepicture', [UserController::class, 'deleteprofilepicture']);

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
            Route::resource('medication', MedicationController::class, ["only" => ['index', 'store', 'update', 'create']]);

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


            Route::post("/dci/destroy", [DciController::class, "destroy"]);
            Route::resource('/dci', DciController::class, [
                'only' => ['store', 'update',]
            ]);

            Route::post("/designation/destroy", [DesignationController::class, "destroy"]);
            Route::resource('/designation', DesignationController::class, [
                'only' => ['store', 'update',]
            ]);


            Route::post("/classification/destroy", [ClassificationController::class, "destroy"]);
            Route::resource('/classification', ClassificationController::class, [
                'only' => ['store', 'update',]
            ]);


            Route::post("/device/destroy", [DeviceController::class, "destroy"]);
            Route::resource('device', DeviceController::class, [
                'only' => ['store', 'update', 'index', 'create']
            ]);

            Route::post("/technicalfile/destroy", [TechnicalFileController::class, "destroy"]);
            Route::resource('technicalfile', TechnicalFileController::class, [
                'only' => ['store', 'update', 'index', 'create']
            ]);
        });

        Route::middleware(['evaluateur'])->group(function () {
            Route::get("/technicalfiles", [TechnicalFileController::class, 'show']);
            Route::get("/document/{id}", [DocumentController::class, 'show'])->where('id', '^[0-9]+$');
            Route::post('/document/{id}/sendComment', [CommentaryController::class, 'store'])->where('id', '^[0-9]+$');
            Route::delete('/document/{id}/destroyComment/{commentId}', [CommentaryController::class, 'destroy'])->where('id', '^[0-9]+$')->where('commentId', '^[0-9]+$');
        });
        Route::middleware('directeur')->group(function () {
            Route::get('/evaluateurs', [UserController::class, 'evaluateurs']);
            Route::post('/technicalfiles/status/{code}', [TechnicalFileController::class, 'changeStatus']);
        });
    });
});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthController;
use \App\Http\Controllers\SearchController;
use \App\Http\Controllers\PosteController;
use \App\Http\Controllers\NotificationController;
use \App\Http\Controllers\StagiaireController;
use \App\Http\Controllers\FiliereController;
use \App\Http\Controllers\ArchiveController;
use App\Http\Controllers\ExcelImportController;



Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::get('user', 'user')->middleware('auth:sanctum');
    Route::post('logout', 'logout')->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {

    Route::resource('poste', PosteController::class);
    Route::resource('archive', ArchiveController::class);
    Route::controller(PosteController::class)->group(function () {
        Route::put('poste/update', 'update');
        Route::post('/poste/{postId}/like', 'likePost');
    });
    Route::resource('filiere', FiliereController::class);
    Route::post('change-password', [AuthController::class, 'changePassword']);


});

Route::get('/downloadpdf', [\App\Http\Controllers\PDFController::class, 'downloadPDF']);
Route::get('/search', [SearchController::class, 'globalSearch']);
Route::get('/edit', [PosteController::class, 'edit']);
Route::resource('postespublic', PosteController::class);
Route::resource('notifs', NotificationController::class);
Route::get('fourstagiaires', [StagiaireController::class, 'randomFourStagiaires']);


Route::get('stagiaire/{id}', [StagiaireController::class, 'index']);
Route::put('stagiaire/{id}', [StagiaireController::class, 'update']);
Route::get('cv/{id}', [StagiaireController::class, 'show']);
Route::post('/stagiaires/{id}/add-propos',[StagiaireController::class, 'addPropos'] );




Route::post('/stagiaires/import', [ExcelImportController::class, 'import']);
Route::get('/import', [ExcelImportController::class, 'importView'])->name('import.view');
Route::post('/stagiaires/import', [ExcelImportController::class, 'import'])->name('import');
Route::post('/search', [ExcelImportController::class, 'search'])->name('search');
Route::get('/stagiairesExc', [ExcelImportController::class, 'index']);


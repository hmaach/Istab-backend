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
use \App\Http\Controllers\EvenementController;
use \App\Http\Controllers\PDFController;
use \App\Http\Controllers\PdfCategorieController;
use App\Http\Controllers\ExcelImportController;

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::get('user', 'user')->middleware('auth:sanctum');
    Route::post('logout', 'logout')->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('poste', PosteController::class);
    Route::resource('events', EvenementController::class);
    Route::resource('filiere', FiliereController::class);
    Route::resource('category', PdfCategorieController::class);


    Route::get('ownarchive', [PdfCategorieController::class, 'index']);
    Route::put('category/update/{category}', [PdfCategorieController::class, 'update']);
    Route::controller(PosteController::class)->group(function () {
        Route::put('poste/update', 'update');
        Route::post('/poste/{postId}/like', 'likePost');
    });

    Route::resource('filiere', FiliereController::class);
    Route::post('change-password', [AuthController::class, 'changePassword']);



    Route::controller(PDFController::class)->group(function () {
        Route::put('pdf/update/{pdf}', 'update');
        Route::post('pdf/removecategory/{pdf}', 'removeCategory');

    });
});

Route::get('archive', [PdfCategorieController::class, 'index']);
Route::resource('/pdf', PDFController::class);

Route::get('/downloadpdf', [PDFController::class, 'downloadPDF']);
Route::get('eventspublic', [EvenementController::class, 'index']);
Route::get('/search', [SearchController::class, 'globalSearch']);
Route::get('/edit', [PosteController::class, 'edit']);
Route::resource('postespublic', PosteController::class);
Route::resource('notifs', NotificationController::class);
Route::get('fourstagiaires', [StagiaireController::class, 'randomFourStagiaires']);


Route::get('stagiaire/{id}', [StagiaireController::class, 'index']);
Route::put('stagiaire/{id}', [StagiaireController::class, 'update']);

Route::put('stagiaire/{id}/competences/{competenceId}', [StagiaireController::class, 'updateCompetences']);
Route::post('/stagiaire/{id}/competences', [StagiaireController::class, 'addCompetence']);


Route::prefix('stagiaires')->group(function () {
    Route::post('{id}/experiences', [StagiaireController::class, 'addExperience']);
    Route::put('{id}/experiences/{experienceId}', [StagiaireController::class, 'updateExperience']);
});


Route::post('/stagiaires/{id}/add-propos', [StagiaireController::class, 'addPropos']);

Route::get('cv/{id}', [StagiaireController::class, 'show']);
Route::post('/stagiaires/{id}/add-propos',[StagiaireController::class, 'addPropos'] );




Route::post('/stagiaires/import', [ExcelImportController::class, 'import']);
Route::get('/import', [ExcelImportController::class, 'importView'])->name('import.view');
Route::post('/stagiaires/import', [ExcelImportController::class, 'import'])->name('import');
Route::post('/search', [ExcelImportController::class, 'search'])->name('search');
Route::get('/stagiairesExc', [ExcelImportController::class, 'index']);



Route::get('/gcv/{id}', [\App\Http\Controllers\CVController::class, 'generate']);

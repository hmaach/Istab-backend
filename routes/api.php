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

    Route::controller(PDFController::class)->group(function () {
        Route::put('pdf/update/{pdf}', 'update');
        Route::post('pdf/removecategory/{pdf}', 'removeCategory');
    });
});

Route::get('archive', [PdfCategorieController::class, 'index']);
Route::resource('/pdf', PDFController::class);

Route::get('/downloadpdf', [PDFController::class, 'downloadPDF']);
Route::get('/search', [SearchController::class, 'globalSearch']);
Route::get('/edit', [PosteController::class, 'edit']);
Route::get('eventspublic', [EvenementController::class, 'index']);
Route::get('showevent/{evenement}', [EvenementController::class, 'show']);
Route::get('postespublic', [PosteController::class, 'index']);
Route::resource('notifs', NotificationController::class);
Route::get('fourstagiaires', [StagiaireController::class, 'randomFourStagiaires']);

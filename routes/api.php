<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthController;
use \App\Http\Controllers\SearchController;
use \App\Http\Controllers\PosteController;
use \App\Http\Controllers\NotificationController;
use \App\Http\Controllers\StagiaireController;
use \App\Http\Controllers\FiliereController;


Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::get('user', 'user')->middleware('auth:sanctum');
    Route::post('logout', 'logout')->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {

    Route::resource('poste',PosteController::class);
    Route::controller(PosteController::class)->group(function () {
        Route::put('poste/update', 'update');
        Route::post('/poste/{postId}/like', 'likePost');
    });
    Route::resource('filiere',FiliereController::class);

});

Route::get('/downloadpdf', [\App\Http\Controllers\PDFController::class, 'downloadPDF']);
Route::get('/search', [SearchController::class, 'globalSearch']);
Route::get('/edit', [PosteController::class, 'edit']);
Route::resource('postespublic', PosteController::class);
Route::resource('notifs', NotificationController::class);
Route::get('fourstagiaires', [StagiaireController::class, 'randomFourStagiaires']);



Route::get('stagiaire/{id}', [StagiaireController::class, 'index']);
Route::put('stagiaire/{id}', [StagiaireController::class, 'update']);


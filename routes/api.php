<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\FileController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/folders', [FolderController::class, 'index']);
    Route::get('/folders/{id}', [FolderController::class, 'show']);
    Route::post('/folders', [FolderController::class, 'create']);
    Route::get('/files', [FileController::class, 'index']);
    Route::get('/files/{id}', [FileController::class, 'show']);
    Route::post('/files/uploads', [FileController::class, 'upload']);
    Route::put('/files/{id}', [FileController::class, 'update']);
    Route::delete('/files/{id}', [FileController::class, 'destroy']);
    Route::put('/files/{id}/move', [FileController::class, 'move']);
    Route::put('/folders/{id}/move', [FolderController::class, 'move']);
});

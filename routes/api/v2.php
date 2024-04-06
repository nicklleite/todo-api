<?php

use App\Http\Controllers\Api\v2\CompleteTaskController;
use App\Http\Controllers\Api\v2\TaskController;
use App\Http\Controllers\Api\v2\SummaryController;
use Illuminate\Support\Facades\Route;

Route::prefix('v2')->middleware('auth:sanctum')->group(function () {
    Route::apiResource('/tasks', TaskController::class)->names("v2_tasks");
    Route::patch('/tasks/{task}/complete', CompleteTaskController::class);
    Route::get('/summaries', SummaryController::class)->name('summaries');
});
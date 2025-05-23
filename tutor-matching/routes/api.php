<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TeacherController;
use App\Http\Controllers\API\EmployerController;

use App\Http\Controllers\API\SurveyController;
use App\Http\Controllers\API\SurveyResponseController;
use App\Http\Controllers\API\ScoutRequestController;
use App\Http\Controllers\API\BookmarkController;
use App\Http\Controllers\API\IndividualContractController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// 認証不要のエンドポイント（ログインしなくても見れる）
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// 認証必須のエンドポイント（ログインしてないと見れない）
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('teachers', TeacherController::class)->except(['create', 'edit']);
    Route::get('/employers/me', [EmployerController::class, 'me']);
    Route::middleware('auth:sanctum')->apiResource('employers', EmployerController::class)->except(['create', 'edit']);

    Route::apiResource('surveys', SurveyController::class)->except(['create', 'edit']);
    Route::apiResource('survey-responses', SurveyResponseController::class)->except(['create', 'edit']);
    Route::apiResource('scout-requests', ScoutRequestController::class)->except(['create', 'edit']);
    Route::apiResource('bookmarks', BookmarkController::class)->except(['create', 'edit']);
    Route::apiResource('individual-contracts', IndividualContractController::class)->except(['create', 'edit']);
    // チャットAPIはChatifyのルーティングに従う
});

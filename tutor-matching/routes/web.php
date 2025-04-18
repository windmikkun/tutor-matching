<?php

use Illuminate\Support\Facades\Route;

/*
    API実装テスト用
*/

Route::get('/teacher-api', function () {
    return view('teacher_api');
});

Route::get('/auth-api', function () {
    return view('auth_api');
});

Route::get('/apitest', function () {
    return view('apitest');
});



/*
|--------------------------------------------------------------------------
| Web Routes ウェブ実装用
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

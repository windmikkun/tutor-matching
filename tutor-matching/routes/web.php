<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// /list route for dynamic redirect
Route::get('/list', function () {
    if (!Auth::check()) {
        return redirect('/');
    }
    $user = Auth::user();
    if ($user->user_type === 'teacher') {
        return redirect('/jobs');
    } elseif ($user->user_type === 'employer') {
        return redirect('/teachers');
    } else {
        return abort(404);
    }
})->middleware(['auth']);

Route::get('/', function () {
    return view('welcome');
})->name('/');

// 講師登録ページ
Route::get('/register/teacher', function () {
    return view('auth.register_teacher');
})->name('register.teacher');
use App\Models\User;
use Illuminate\Support\Facades\Hash;

Route::post('/register/teacher', function (\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
    ]);
    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'user_type' => 'teacher',
    ]);
    // Teacherプロフィールも同時作成
    \App\Models\Teacher::create([
        'user_id' => $user->id,
        'first_name' => '',
        'last_name' => '',
    ]);
    Auth::login($user);
    return redirect()->route('dashboard.teacher');
});

// 法人登録ページ
Route::get('/register/employer', function () {
    return view('auth.register_employer');
})->name('register.employer');
Route::post('/register/employer', function (\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
    ]);
    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'user_type' => 'corporate_employer',
    ]);
    Auth::login($user);
    return redirect()->route('dashboard.teacher');
});

use App\Http\Controllers\DashboardController;

Route::get('/dashboard', function () {
    Route::get('/scouts/{id}', [App\Http\Controllers\ScoutController::class, 'show'])->name('scout.detail');
    // 役割でリダイレクト or 案内用
    $user = Auth::user();
    if ($user && $user->user_type === 'teacher') {
        return redirect('/dashboard/teacher');
    } elseif ($user && in_array($user->user_type, ['individual_employer', 'corporate_employer'])) {
        return redirect('/dashboard/employer');
    }
    return view('dashboard'); // 万一のため案内ページ
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard/teacher', [DashboardController::class, 'teacher'])->middleware(['auth', 'verified'])->name('dashboard.teacher');
Route::get('/dashboard/employer', [DashboardController::class, 'employer'])->middleware(['auth', 'verified'])->name('dashboard.employer');
use App\Http\Controllers\EmployerProfileController;
use App\Http\Controllers\TeacherProfileController;

Route::get('/employer/profile/show', [EmployerProfileController::class, 'show'])->middleware(['auth', 'verified'])->name('employer.profile.show');
Route::get('/employer/profile', [EmployerProfileController::class, 'edit'])->middleware(['auth', 'verified'])->name('employer.profile.edit');
Route::put('/employer/profile', [EmployerProfileController::class, 'update'])->middleware(['auth', 'verified'])->name('employer.profile.update');

Route::get('/teacher/profile', [TeacherProfileController::class, 'edit'])->middleware(['auth', 'verified'])->name('teacher.profile.edit');
Route::put('/teacher/profile', [TeacherProfileController::class, 'update'])->middleware(['auth', 'verified'])->name('teacher.profile.update');

Route::middleware('auth')->group(function () {
    // ブックマーク一覧
    Route::get('/bookmarks', function () {
        // 本来はコントローラでDBから取得するが、ここではデモ用に空配列
        $bookmarks = [];
        return view('bookmarks', compact('bookmarks'));
    });

    // 求人リスト・講師リスト自動振り分け
    Route::get('/jobs', function () {
        $user = Auth::user();
        if (!$user) {
            return redirect('/login');
        }
        if (method_exists($user, 'isTeacher') && $user->isTeacher()) {
            // 講師：塾リストを表示
            $jobs = \App\Models\Employer::all();
            return view('jobs', compact('jobs'));
        } elseif (method_exists($user, 'isEmployer') && $user->isEmployer()) {
            // 求人者：講師リストへリダイレクト
            return redirect('/teachers');
        } else {
            return redirect('/')->with('error', 'このページにはアクセスできません');
        }
    });

    // 求人詳細
    Route::get('/jobs/{id}', function($id) {
        $job = \App\Models\Employer::findOrFail($id);
        return view('jobs_show', compact('job'));
    })->name('job.show');

    // 応募作成（ダミー）
    Route::get('/entry/create/{id}', function($id) {
        return '応募機能は準備中です。';
    })->name('entry.create');

    // Scout（スカウト）関連
    Route::get('/scout/list', [\App\Http\Controllers\ScoutController::class, 'scoutList'])->name('scout.list');
    Route::get('/scout/{id}', [\App\Http\Controllers\ScoutController::class, 'create'])->name('scout.create');
    Route::post('/scout/{id}/confirm', [\App\Http\Controllers\ScoutController::class, 'confirm'])->name('scout.confirm');
    Route::post('/scout/{id}/confirm/send', [\App\Http\Controllers\ScoutController::class, 'confirmSend'])->name('scout.confirm.send');
    Route::post('/scout/{id}', [\App\Http\Controllers\ScoutController::class, 'store'])->name('scout.store');
    // Chatify（DMのみ）
    Route::get('/chat', function () {
        return redirect('/chatify');
    })->name('chat');
    // 講師リスト
    Route::get('/teachers', [\App\Http\Controllers\TeacherListController::class, 'index'])->name('teacher.list');
    Route::get('/teachers/{id}', [\App\Http\Controllers\TeacherListController::class, 'show'])->name('teacher.show');

});

require __DIR__.'/auth.php'; // Laravel Breeze/Fortify等の認証ルートを有効化

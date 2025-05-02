<?php

use Illuminate\Support\Facades\Route;

// プロフィール画像アップロードAPI
Route::post('/profile/image/upload', [\App\Http\Controllers\ProfileController::class, 'uploadProfileImage'])
    ->middleware(['auth', 'verified'])
    ->name('profile.image.upload');

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\API\BookmarkController; // ブックマークAPI用
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
    if (Auth::check()) {
        $user = Auth::user();
        if (method_exists($user, 'isEmployer') && $user->isEmployer()) {
            return redirect('/teachers');
        }
    }
    return view('welcome');
})->name('/');

// 求人リストページ
use App\Http\Controllers\JobListController;
Route::get('/jobs', [JobListController::class, 'index'])->name('jobs');

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
    if ($user && ($user->user_type === 'teacher')) {
        return redirect()->route('dashboard.teacher');
    } elseif ($user && (in_array($user->user_type, ['corporate_employer', 'employer']))) {
        return redirect()->route('dashboard.employer');
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

// 講師用 会員情報編集（メール・パスワード・電話番号等）
Route::get('/teacher/account', [ProfileController::class, 'edit'])->middleware(['auth', 'verified'])->name('teacher.account.edit');
Route::put('/teacher/account', [ProfileController::class, 'update'])->middleware(['auth', 'verified'])->name('teacher.account.update');
Route::delete('/teacher/account', [ProfileController::class, 'destroy'])->middleware(['auth', 'verified'])->name('teacher.account.destroy');
Route::match(['get', 'post'], '/teacher/account/confirm', function(\Illuminate\Http\Request $request) {
    if ($request->isMethod('post')) {
        session(['inputs' => $request->all()]);
        return redirect()->route('teacher.account.confirm');
    }
    $inputs = session('inputs', []);
    return view('teacher.account_confirm', compact('inputs'));
})->middleware(['auth', 'verified'])->name('teacher.account.confirm');

// 講師プロフィール更新完了画面
Route::get('/teacher/profile/update/complete', function () {
    return view('teacher.profile_update_complete');
})->middleware(['auth', 'verified'])->name('teacher.profile.update.complete');

// 雇用者用 会員情報編集（メール・パスワード・電話番号等）
Route::get('/employer/account', [ProfileController::class, 'edit'])->middleware(['auth', 'verified'])->name('employer.account.edit');
Route::post('/employer/account', [ProfileController::class, 'confirm'])->middleware(['auth', 'verified'])->name('employer.account.confirm');
Route::put('/employer/account', [ProfileController::class, 'update'])->middleware(['auth', 'verified'])->name('employer.account.update');
Route::delete('/employer/account', [ProfileController::class, 'destroy'])->middleware(['auth', 'verified'])->name('employer.account.destroy');

// 講師詳細ページ（ブックマーク数取得用）
Route::get('/teacher/{id}', [\App\Http\Controllers\TeacherListController::class, 'show'])->name('teacher_show');

Route::middleware('auth')->group(function () {
    // ブックマーク登録・削除API
    Route::post('/bookmarks', [BookmarkController::class, 'store'])->name('bookmarks.store');
    Route::delete('/bookmarks/{bookmark}', [BookmarkController::class, 'destroy'])->name('bookmarks.destroy');
    // 講師ブックマーク一覧ページ（講師ユーザーは雇用者（求人）のみ表示）
    Route::get('/bookmarks', [\App\Http\Controllers\API\BookmarkController::class, 'teacherBookmarks'])->name('bookmarks.list');

    // 求人詳細
    Route::get('/jobs/{id}', function($id) {
        $job = \App\Models\Employer::findOrFail($id);
        $isBookmarked = false;
        $bookmarkCount = $job->bookmarkedByTeachers()->count();
        if (auth()->check()) {
            $isBookmarked = \App\Models\Bookmark::where('user_id', auth()->id())
                ->where('bookmarkable_type', 'employer') // 小文字に統一
                ->where('bookmarkable_id', $job->id)
                ->exists();
        }
        return view('jobs_show', compact('job', 'isBookmarked', 'bookmarkCount'));
    })->name('job.show');

    // 応募確認ページ
    Route::get('/entry/create/{id}', function($id) {
        $job = \App\Models\Employer::findOrFail($id);
        return view('entry_confirm', compact('job'));
    })->name('entry.create');

    // 応募処理
    Route::post('/entry/store/{id}', function($id) {
        $user = Auth::user();
        if (!$user) {
            return redirect('/login');
        }
        $employer = \App\Models\Employer::findOrFail($id);
        // 重複応募防止
        $exists = \App\Models\Entry::where('user_id', $user->id)->where('employer_id', $employer->id)->exists();
        if ($exists) {
            return redirect('/jobs')->with('error', 'すでに応募済みです');
        }
        \App\Models\Entry::create([
            'user_id' => $user->id,
            'employer_id' => $employer->id,
        ]);
        return redirect('/jobs')->with('status', '応募が完了しました');
    })->name('entry.store');

    // 応募済一覧

    Route::get('/entries/applicants', function() {
        $user = auth()->user();
        if (!$user || !in_array($user->user_type, ['employer', 'corporate_employer'])) {
            abort(403);
        }
        $employer = $user->employer; // employersテーブルのidを使う->employer;
        $entries = \App\Models\Entry::with('user.teacher')->where('employer_id', $employer->id)->get();
        return view('teacher_list', ['entries' => $entries]);
    })->name('entries.applicants');

    Route::get('/entries', function() {
        return view('entries');
    })->name('entries.list');

    // Scout（スカウト）関連
    Route::get('/scouts', [\App\Http\Controllers\ScoutController::class, 'scoutList'])->name('scout.list');
    Route::get('/scout/{id}', [\App\Http\Controllers\ScoutController::class, 'create'])->name('scout.create');
    Route::post('/scout/{id}/confirm', [\App\Http\Controllers\ScoutController::class, 'confirm'])->name('scout.confirm');
    Route::post('/scout/{id}/confirm/send', [\App\Http\Controllers\ScoutController::class, 'confirmSend'])->name('scout.confirm.send');
    Route::post('/scout/{id}', [\App\Http\Controllers\ScoutController::class, 'store'])->name('scout.store');
    Route::post('/scout/{id}/reject', [\App\Http\Controllers\ScoutController::class, 'reject'])->name('scout.reject');
    // Chatify（DMのみ）
    Route::get('/chat', function () {
        return redirect('/chatify');
    })->name('chat');
    Route::get('/chat/{id}', function ($id) {
        // Chatifyのユーザー個別チャットページへリダイレクト
        return redirect('/chatify/' . $id);
    })->name('chat.show');
    // 応募者拒否（employerが応募者をリストから削除）
    Route::post('/applicant/{id}/reject', function($id) {
        $user = Auth::user();
        $employer = $user ? $user->employer : null;
        \Log::info('[REJECT] incoming id: ' . $id);
        \Log::info('[REJECT] employer: ', $employer ? $employer->toArray() : []);
        if (!$employer || !in_array($user->user_type, ['employer', 'corporate_employer'])) {
            \Log::warning('[REJECT] aborting: user not employer');
            abort(403);
        }
        $entry = \App\Models\Entry::find($id);
        \Log::info('[REJECT] found entry: ', $entry ? $entry->toArray() : []);
        \Log::info('[REJECT DEBUG] employer_id (employer): ' . ($employer ? $employer->id : 'null'));
        \Log::info('[REJECT DEBUG] employer_id (entry): ' . ($entry ? $entry->employer_id : 'null'));
        $deleted = 0;
        if ($entry && $entry->employer_id == $employer->id) {
            $entry->delete();
            $deleted = 1;
            \Log::info('[REJECT] entry deleted');
        } else {
            \Log::warning('[REJECT] entry not deleted: entry missing or employer_id mismatch');
        }
        \Log::info('[REJECT] Entry deleted count: ' . $deleted);
        return redirect()->route('entries.applicants')->with('success', '応募者を拒否しました（削除件数: ' . $deleted . '）');
    })->name('applicant.reject');

    // 講師リスト
    Route::get('/teachers', [\App\Http\Controllers\TeacherListController::class, 'index'])->name('teacher.list');
    Route::get('/teachers/{id}', [\App\Http\Controllers\TeacherListController::class, 'show'])->name('teacher.show');

});

// 塾（employer）詳細ページ
Route::get('/employer/{id}', function($id) {
    $employer = \App\Models\Employer::findOrFail($id);
    $bookmarkCount = $employer->bookmarkedByTeachers()->count();
    $isBookmarked = false;
    if (auth()->check()) {
        $isBookmarked = $employer->bookmarkedByTeachers()->where('user_id', auth()->id())->exists();
    }
    return view('employer_show', compact('employer', 'bookmarkCount', 'isBookmarked'));
})->name('employer.show');

require __DIR__.'/auth.php'; // Laravel Breeze/Fortify等の認証ルートを有効化

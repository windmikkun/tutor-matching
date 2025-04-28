<?php

use Illuminate\Support\Facades\Route;

// プロフィール画像アップロードAPI
Route::post('/profile/image/upload', [\App\Http\Controllers\ProfileController::class, 'uploadProfileImage'])->name('profile.image.upload');

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

// 講師詳細ページ（ブックマーク数取得用）
Route::get('/teacher/{id}', [\App\Http\Controllers\TeacherListController::class, 'show'])->name('teacher_show');

Route::middleware('auth')->group(function () {
    // ブックマーク登録・削除API
    Route::post('/bookmarks', [BookmarkController::class, 'store'])->name('bookmarks.store');
    Route::delete('/bookmarks/{bookmark}', [BookmarkController::class, 'destroy'])->name('bookmarks.destroy');
    // ブックマーク一覧
    Route::get('/bookmarks', function () {
        $user = Auth::user();
        $bookmarks = \App\Models\Bookmark::where('user_id', $user->id)->get();
        $items = [];
        foreach ($bookmarks as $bookmark) {
            if ($bookmark->bookmarkable_type === 'Employer') {
                $employer = \App\Models\Employer::find($bookmark->bookmarkable_id);
                if ($employer) $items[] = $employer;
            } elseif ($bookmark->bookmarkable_type === 'Teacher') {
                $teacher = \App\Models\Teacher::find($bookmark->bookmarkable_id);
                if ($teacher) $items[] = $teacher;
            }
        }
        return view('bookmarks', ['bookmarks' => $items]);
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
        $entries = \App\Models\Entry::with('user.teacher')->orderByDesc('created_at')->get();
        $teachers = $entries->map(function($entry) {
            return $entry->user->teacher;
        })->filter();
        return view('teacher_list', ['teachers' => $teachers]);
    });
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

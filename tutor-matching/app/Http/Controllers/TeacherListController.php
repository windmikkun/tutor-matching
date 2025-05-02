<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log; // 追加

use Illuminate\Http\Request;
use App\Models\Teacher;

class TeacherListController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $user = auth()->user();
        if (!$user || !in_array($user->user_type, ['employer', 'corporate_employer'])) {
            abort(403);
        }
        // 全講師を取得
        $teachers = \App\Models\Teacher::with('user')->orderBy('updated_at', 'desc')->get();
        return view('teacher_list', compact('teachers'));
    }

    public function show($id)
    {
        $teacher = Teacher::with('user')->findOrFail($id);
        $bookmarkCount = $teacher->bookmarkedByEmployers()->count();
        Log::debug([
            'teacher_id' => $teacher->id,
            'bookmark_count' => $bookmarkCount,
            'bookmarkable_type_all' => \App\Models\Bookmark::where('bookmarkable_id', $teacher->id)->pluck('bookmarkable_type')->toArray(),
        ]);
        // ログインユーザーがこの講師をブックマーク済みか判定
        $isBookmarked = false;
        $currentUserBookmarks = collect();
        if (auth()->check()) {
            $user = auth()->user();
            $currentUserBookmarks = $user->bookmarks->where('bookmarkable_type', 'teacher');
            $isBookmarked = $currentUserBookmarks->where('bookmarkable_id', $teacher->id)->isNotEmpty();
        }
        return view('teacher_show', compact('teacher', 'bookmarkCount', 'isBookmarked', 'currentUserBookmarks'));
    }
}

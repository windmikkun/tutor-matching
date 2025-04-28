<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log; // 追加

use Illuminate\Http\Request;
use App\Models\Teacher;

class TeacherListController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with('user')->get();
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
        if (auth()->check()) {
            $isBookmarked = \App\Models\Bookmark::where('user_id', auth()->id())
                ->where('bookmarkable_type', 'teacher') // morphMapで小文字化
                ->where('bookmarkable_id', $teacher->id)
                ->exists();
        }
        return view('teacher_show', compact('teacher', 'bookmarkCount', 'isBookmarked'));
    }
}

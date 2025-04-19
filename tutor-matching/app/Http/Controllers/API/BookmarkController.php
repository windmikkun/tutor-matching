<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Bookmark;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    /**
     * ブックマーク一覧取得
     * -ログインユーザーの前ブックマークを返す
     */
    public function index(Request $request)
    {
        $user = $request->user();
        // 自分のブックマークだけ取得
        $bookmarks = \App\Models\Bookmark::where('user_id', $user->id)->get();
        // idをbookmark_idとして返す
        $bookmarks = $bookmarks->map(function($b) {
            $arr = $b->toArray();
            $arr['bookmark_id'] = $b->id;
            return $arr;
        });
        return response()->json($bookmarks);
    }

    /**
     * ブックマーク登録
     * -既にブックマークしている場合は409
     */
    public function store(Request $request)
    {
    $user = $request->user();

    // ユーザー種別によってバリデーションと保存データを分岐
    if ($user->user_type === 'teacher') {
        // teacherはemployerのみブックマーク可能
        $validated = $request->validate([
            'employer_id' => 'required|exists:employers,id',
        ]);
        $bookmarkable_type = 'Employer';
        $bookmarkable_id = $validated['employer_id'];
    } elseif (in_array($user->user_type, ['individual_employer', 'corporate_employer'])) {
        // employerはteacherのみブックマーク可能
        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
        ]);
        $bookmarkable_type = 'Teacher';
        $bookmarkable_id = $validated['teacher_id'];
    } else {
        return response()->json(['message' => '不正なユーザー種別です'], 403);
    }

    // 既に同じ相手をブックマークしていないかチェック
    $exists = \App\Models\Bookmark::where('user_id', $user->id)
        ->where('bookmarkable_type', $bookmarkable_type)
        ->where('bookmarkable_id', $bookmarkable_id)
        ->exists();

    if ($exists) {
        return response()->json(['message' => '既にブックマークしています'], 409);
    }

    // 新規ブックマーク作成
    $bookmark = \App\Models\Bookmark::create([
        'user_id' => $user->id,
        'bookmarkable_type' => $bookmarkable_type,
        'bookmarkable_id' => $bookmarkable_id,
    ]);

    return response()->json($bookmark, 201);
    }

    /**
     * ブックマーク削除
     */
    public function destroy(Bookmark $bookmark)
    {
        // ログインユーザーのブックマークのみ削除可能
        if ($bookmark->user_id !== auth()->id()) {
            return response()->json(['message' => '権限がありません'], 403);
        }
        $bookmark->delete();
        return response()->json(['message' => 'ブックマークを削除しました']);
    }

}


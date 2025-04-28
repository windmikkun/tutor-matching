<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Bookmark;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    /**
     * ブックマーク一覧取得（ログインユーザー分のみ）
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $bookmarks = Bookmark::where('user_id', $user->id)->get()
            ->map(function($b) {
                $arr = $b->toArray();
                $arr['bookmark_id'] = $b->id;
                return $arr;
            });
        return response()->json($bookmarks);
    }

    /**
     * ブックマーク登録（重複時409）
     */
    public function store(Request $request)
    {
        try {
            $user = $request->user();
            // ユーザー種別ごとにバリデーション
            if ($user->user_type === 'teacher') {
                $validated = $request->validate([
                    'employer_id' => 'required|exists:employers,id',
                ]);
                $bookmarkable_type = 'employer'; // 小文字に統一
                $bookmarkable_id = $validated['employer_id'];
            } elseif (in_array($user->user_type, ['individual_employer', 'corporate_employer'])) {
                $validated = $request->validate([
                    'teacher_id' => 'required|exists:teachers,id',
                ]);
                $bookmarkable_type = 'teacher'; // 小文字に統一
                $bookmarkable_id = $validated['teacher_id'];
            } else {
                return response()->json([
                    'message' => '不正なユーザー種別です',
                    'error' => 'forbidden',
                ], 403);
            }

            // 重複チェック
            $exists = Bookmark::where('user_id', $user->id)
                ->where('bookmarkable_type', $bookmarkable_type)
                ->where('bookmarkable_id', $bookmarkable_id)
                ->exists();

            if ($exists) {
                return response()->json([
                    'message' => '既にブックマークしています',
                    'error' => 'already_bookmarked',
                ], 409);
            }

            // 登録
            $bookmark = Bookmark::create([
                'user_id' => $user->id,
                'bookmarkable_type' => $bookmarkable_type,
                'bookmarkable_id' => $bookmarkable_id,
            ]);

            // 最新のブックマーク数を取得
            if ($bookmarkable_type === 'employer') {
                $model = \App\Models\Employer::find($bookmarkable_id);
                $bookmarkCount = $model ? $model->bookmarkedByTeachers()->count() : 0;
            } elseif ($bookmarkable_type === 'teacher') {
                $model = \App\Models\Teacher::find($bookmarkable_id);
                $bookmarkCount = $model ? $model->bookmarkedByEmployers()->count() : 0;
            } else {
                $bookmarkCount = 0;
            }

            return response()->json([
                'bookmarked' => true,
                'bookmarkCount' => $bookmarkCount
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'バリデーションエラー',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => '予期しないエラーが発生しました',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * ブックマーク削除（権限チェックあり）
     */
    public function destroy(Bookmark $bookmark)
    {
        try {
            if ($bookmark->user_id !== auth()->id()) {
                return response()->json([
                    'message' => '権限がありません',
                    'error' => 'forbidden',
                ], 403);
            }
            // 削除後の最新ブックマーク数を取得
            $bookmarkable_type = $bookmark->bookmarkable_type;
            $bookmarkable_id = $bookmark->bookmarkable_id;
            $bookmark->delete();

            if ($bookmarkable_type === 'Employer') {
                $model = \App\Models\Employer::find($bookmarkable_id);
                $bookmarkCount = $model ? $model->bookmarkedByTeachers()->count() : 0;
            } elseif ($bookmarkable_type === 'Teacher') {
                $model = \App\Models\Teacher::find($bookmarkable_id);
                $bookmarkCount = $model ? $model->bookmarkedByEmployers()->count() : 0;
            } else {
                $bookmarkCount = 0;
            }

            return response()->json([
                'bookmarked' => false,
                'bookmarkCount' => $bookmarkCount,
                'message' => 'ブックマークを削除しました'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => '予期しないエラーが発生しました',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}


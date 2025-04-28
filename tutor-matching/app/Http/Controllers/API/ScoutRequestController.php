<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ScoutRequest;
use Illuminate\Http\Request;

class ScoutRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * スカウト一覧取得API
     * - employer（employer）は「自分が送信したスカウト一覧」を取得
     * - teacherは「自分が受け取ったスカウト一覧」を取得
     * - それ以外のユーザーは権限エラー
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // ▼ employer（雇用者）ユーザーの場合: 自分が送信したスカウト一覧
        if (in_array($user->user_type, ['employer'])) {
            // 雇用者プロファイル取得
            $employerId = $user->employer->id ?? null;
            if (!$employerId) {
                // 雇用者プロファイルがなければ空配列
                return response()->json([], 200);
            }
            // teacher情報もネストして返却
            $scouts = \App\Models\ScoutRequest::with(['teacher.user'])
                ->where('employer_id', $employerId)
                ->orderByDesc('created_at')
                ->get();
            return response()->json($scouts);
        }

        // ▼ teacherユーザーの場合: 自分が受け取ったスカウト一覧
        if ($user->user_type === 'teacher') {
            // 講師プロファイル取得
            $teacherId = $user->teacher->id ?? null;
            if (!$teacherId) {
                // 講師プロファイルがなければ空配列
                return response()->json([], 200);
            }
            // employer情報もネストして返却
            $scouts = \App\Models\ScoutRequest::with(['employer.user'])
                ->where('teacher_id', $teacherId)
                ->orderByDesc('created_at')
                ->get();
            return response()->json($scouts);
        }

        // ▼ その他ユーザーは権限なし
        return response()->json([
            'message' => '権限がありません',
            'error' => 'unauthorized'
        ], 403);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //1.ログインユーザーの取得
        $user = $request->user();

        //雇用者ではない場合拒否
        if (!in_array($user->user_type, ['employer'])) {
            return response()->json([
                'message' => '権限がありません',
                'error' => 'unauthorized'
            ], 403);
        }

        //バリデーション
        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'message' => 'required|string|max:500',
        ]);

        //2. ScoutRequestの作成
        $scout = ScoutRequest::create([
            'employer_id' => $user->employer->id,
            'teacher_id' => $validated['teacher_id'],
            'message' => $validated['message'],
            'status' => 'pending',
        ]);

        //5.作成したスカウトを返却
        return response()->json($scout);
    }

    /**
     * Display the specified resource.
     */
    /**
     * スカウトの詳細を取得する
     * 関係者（送信者または受信者）のみアクセス可能
     */
    public function show(ScoutRequest $scoutRequest)
    {
        // 1. ログインユーザーを取得
        $user = request()->user();

        // 2. 関係者（送信者または受信者）以外はアクセス拒否
        if (
            $user->id !== $scoutRequest->employer?->user_id &&
            $user->id !== $scoutRequest->teacher?->user_id
        ) {
            // 403エラーを返す（権限なし）
            return response()->json([
                'message' => '権限がありません',
                'error' => 'unauthorized'
            ], 403);
        }

        // 3. スカウト情報を返却
        return response()->json($scoutRequest);
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * スカウト情報を更新する
     * 関係者（送信者または受信者）のみ更新可能
     */
    public function update(Request $request, ScoutRequest $scoutRequest)
    {
        // 1. ログインユーザーを取得
        $user = $request->user();

        // 2. 関係者（送信者または受信者）以外はアクセス拒否
        if (
            $user->id !== $scoutRequest->employer?->user_id &&
            $user->id !== $scoutRequest->teacher?->user_id
        ) {
            // 403エラーを返す（権限なし）
            return response()->json([
                'message' => '権限がありません',
                'error' => 'unauthorized'
            ], 403);
        }

        // 3. バリデーション（messageとstatusのみ更新可能）
        $validated = $request->validate([
            'message' => 'sometimes|required|string|max:500', // メッセージは省略可・最大500文字
            'status' => 'sometimes|required|in:pending,accepted,rejected,canceled', // ステータスは4種類のみ
        ]);

        // 4. バリデーションを通過した値で更新
        $scoutRequest->fill($validated);
        $scoutRequest->save();

        // 5. 更新後のスカウト情報を返却
        return response()->json($scoutRequest);
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * スカウトを削除する
     * 送信者（employer）のみ削除可能
     */
    public function destroy(ScoutRequest $scoutRequest)
    {
        // 1. ログインユーザーを取得
        $user = request()->user();

        // 2. 送信者（employer）以外は削除不可
        if ($user->id !== $scoutRequest->employer?->user_id) {
            // 403エラーを返す（権限なし）
            return response()->json([
                'message' => '権限がありません',
                'error' => 'unauthorized'
            ], 403);
        }

        // 3. スカウトを削除
        $scoutRequest->delete();

        // 4. 削除完了メッセージを返却
        return response()->json(['message' => 'スカウトを削除しました']);
    }
}

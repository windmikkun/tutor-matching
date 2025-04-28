<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log; // 追加

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Chatify\Facades\ChatifyMessenger as Chatify;

class ScoutController extends Controller
{
    // スカウト一覧表示（講師が受け取った分のみ）
    public function scoutList()
    {
        $user = auth()->user();
        $teacher = $user->teacher;
        $scouts = [];
        if ($teacher) {
            $scouts = \App\Models\ScoutRequest::with(['employer.user'])
                ->where('teacher_id', $teacher->id)
                ->where('status', 'pending') // 未対応のみ表示
                ->orderBy('id', 'desc')->get();
        }
        return view('scout_list', compact('scouts'));
    }

    // アンケートページ表示
    public function create($id)
    {
        return view('scout_survey', ['teacher_id' => $id]);
    }

    // スカウト拒否
    public function reject($id)
    {
        $scout = \App\Models\ScoutRequest::findOrFail($id);
        $user = auth()->user();
        $teacher = $user->teacher;
        if ($scout->teacher_id !== $teacher->id) {
            abort(403, '権限がありません');
        }
        $scout->status = 'rejected';
        $scout->save();
        return redirect()->route('scout.list')->with('success', 'スカウトを拒否しました');
    }

    // 確認画面表示
    public function confirm(Request $request, $id)
    {
        $message = $request->input('message');
        return view('scout_survey_confirm', [
            'teacher_id' => $id,
            'message' => $message,
        ]);
    }

    // 確認画面での最終送信（実際の保存や通知は行わずリダイレクトのみ）
    public function confirmSend(Request $request, $id)
    {
        $user = auth()->user();
        // 1. 雇用者側：新規スカウト送信
        if ($user->employer) {
            $employer = $user->employer;
            $message = $request->input('message');
            // 重複スカウト防止バリデーション
            $exists = \App\Models\ScoutRequest::where('employer_id', $employer->id)
                ->where('teacher_id', $id)
                ->where('status', 'pending')
                ->exists();
            if ($exists) {
                return redirect()->back()->with('error', 'すでに同じ講師にスカウト済みです');
            }
            $scout = new \App\Models\ScoutRequest();
            $scout->employer_id = $employer->id;
            $scout->teacher_id = $id;
            $scout->message = $message;
            $scout->status = 'pending';
            $scout->save();
            // DM送信
            $teacher = \App\Models\Teacher::find($id);
            if ($teacher && $teacher->user_id) {
                \Log::debug('雇用者→講師へDM送信', [
                    'from_id' => $user->id,
                    'to_id' => $teacher->user_id,
                    'body' => $message,
                ]);
                Chatify::newMessage([
                    'from_id' => $user->id,
                    'to_id' => $teacher->user_id,
                    'body' => $message,
                    'attachment' => null,
                ]);
            } else {
                \Log::warning('スカウト時にteacherまたはuser_idが見つからずDM送信できません', [
                    'teacher' => $teacher,
                    'teacher_id' => $id,
                ]);
            }
            // 完了画面
            return view('scout_complete');
        }
        // 2. 講師側：承諾処理
        Log::debug('teacherプロパティ確認', [
            'user_id' => $user->id,
            'user_type' => $user->user_type,
            'teacher' => $user->teacher,
        ]);
        if ($user->teacher) {
            Log::debug('承諾処理開始', [
                'user_id' => $user->id,
                'scout_id' => $id,
                'user_type' => $user->user_type,
            ]);
            // 既存のスカウトリクエストを取得
            $scout = \App\Models\ScoutRequest::findOrFail($id);
            // ステータスをacceptedに変更
            $scout->status = 'accepted';
            $scout->save();
            // 雇用者からのアンケート内容をDM送信
            $employerUser = $scout->employer->user;
            $teacherUser = $user;
            if ($employerUser && $teacherUser) {
                \Log::debug('講師承諾時に雇用者→講師へDM送信', [
                    'from_id' => $employerUser->id,
                    'to_id' => $teacherUser->id,
                    'body' => $scout->message,
                ]);
                Chatify::newMessage([
                    'from_id' => $employerUser->id,
                    'to_id' => $teacherUser->id,
                    'body' => $scout->message,
                    'attachment' => null,
                ]);
            } else {
                \Log::warning('講師承諾時のDM送信でユーザー情報が見つからない', [
                    'employerUser' => $employerUser,
                    'teacherUser' => $teacherUser,
                    'scout_id' => $id,
                ]);
            }
            // DMページへ遷移
            return redirect()->route('chat')->with('status', 'アンケートがDMで送信されました');
        }
        // どちらでもなければリダイレクト
        return redirect('/')->with('error', '不正な操作です');
    }

    // アンケート送信処理（ダミー）
    public function store(Request $request, $id)
    {
        // バリデーションや保存処理をここに記述
        // 今回はダミーでリダイレクトのみ
        return redirect()->route('teacher.show', ['id' => $id])->with('success', 'スカウトを送信しました');
    }

    // スカウト詳細表示
    public function show($id)
    {
        $scout = \App\Models\ScoutRequest::findOrFail($id);
        if ($scout->status === 'new') {
            $scout->status = 'pending';
            $scout->save();
        }
        return view('scout_detail', compact('scout'));
    }
}


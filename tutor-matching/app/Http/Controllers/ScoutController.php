<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ScoutController extends Controller
{
    // スカウト一覧表示
    public function scoutList()
    {
        $scouts = \App\Models\ScoutRequest::orderBy('id', 'desc')->get();
        return view('scout_list', compact('scouts'));
    }

    // アンケートページ表示
    public function create($id)
    {
        return view('scout_survey', ['teacher_id' => $id]);
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
        $employer = $user->employer;
        if (!$employer) {
            return redirect()->route('teacher.list')->with('error', '雇用者のみスカウト可能です');
        }
        $message = $request->input('message');
        $scout = new \App\Models\ScoutRequest();
        $scout->employer_id = $employer->id;
        $scout->teacher_id = $id;
        $scout->message = $message;
        $scout->status = 'pending';
        $scout->save();
        return view('scout_complete');
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


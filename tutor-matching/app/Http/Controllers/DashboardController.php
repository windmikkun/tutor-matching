<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // 講師用ダッシュボード
    public function teacher()
    {
        $user = Auth::user();
        // teacherリレーションがなければ自動作成
        if ($user->user_type === 'teacher' && !$user->teacher) {
            \App\Models\Teacher::create([
                'user_id' => $user->id,
                'first_name' => '',
                'last_name' => '',
                'profile_image' => '',
                'phone' => '',
                'address' => '',
                'nearest_station' => '',
                'university' => '',
                'faculty' => '',
                'graduation_year' => null,
                'teaching_experience' => 0,
                'subject' => '',
                'grade_level' => '',
            ]);
            // リレーションをリロード
            $user->refresh();
        }
        // teacherユーザー以外はemployerダッシュボードへリダイレクト
        if (!$user->teacher) {
            return redirect()->route('dashboard.employer');
        }
        $teacher = $user->teacher;
        // 実データ: スカウト状況
        $scoutCounts = [
            'new' => $teacher ? $teacher->scoutRequests()->where('status', 'new')->count() : 0,
            'pending' => $teacher ? $teacher->scoutRequests()->where('status', 'pending')->count() : 0,
            'matched' => $teacher ? $teacher->scoutRequests()->where('status', 'matched')->count() : 0,
        ];
        $newScouts = [];
        // 最新のチャット（部屋単位で最新1件ずつ、最大5名分）
        $userId = $user->id;
        $contactIds = \App\Models\ChMessage::where('from_id', $userId)
            ->orWhere('to_id', $userId)
            ->selectRaw('IF(from_id = ?, to_id, from_id) as contact_id', [$userId])
            ->groupBy('contact_id')
            ->pluck('contact_id');
        $latestChats = collect();
        foreach ($contactIds as $contactId) {
            $msg = \App\Models\ChMessage::where(function($q) use ($userId, $contactId) {
                    $q->where('from_id', $userId)->where('to_id', $contactId);
                })->orWhere(function($q) use ($userId, $contactId) {
                    $q->where('from_id', $contactId)->where('to_id', $userId);
                })
                ->orderByDesc('created_at')
                ->first();
            if ($msg) $latestChats->push($msg);
        }
        $latestChats = $latestChats->sortByDesc('created_at')->take(5);
        return view('dashboard_teacher', [
            'teacher' => $teacher,
            'scoutCounts' => $scoutCounts,
            'newScouts' => $newScouts,
            'latestMessages' => $latestChats,
        ]);
    }

    // 求人側ダッシュボード
    public function employer()
    {
        $user = Auth::user();
        // employerもteacherもなければトップへ
        if (!$user->employer && !$user->teacher) {
            return redirect()->route('/');
        }
        // employerレコードがなければプロフィール編集へ
        if (!$user->employer) {
            return redirect()->route('employer.profile.edit');
        }
        $employer = $user->employer;
        // 実データ: スカウト状況
        $scoutCounts = [
            'new' => $employer ? $employer->scoutRequests()->where('status', 'new')->count() : 0,
            'pending' => $employer ? $employer->scoutRequests()->where('status', 'pending')->count() : 0,
            'matched' => $employer ? $employer->scoutRequests()->where('status', 'matched')->count() : 0,
        ];
        $newScouts = [];
        // 最新のチャット（部屋単位で最新1件ずつ、最大5名分）
        $userId = $user->id;
        $contactIds = \App\Models\ChMessage::where('from_id', $userId)
            ->orWhere('to_id', $userId)
            ->selectRaw('IF(from_id = ?, to_id, from_id) as contact_id', [$userId])
            ->groupBy('contact_id')
            ->pluck('contact_id');
        $latestChats = collect();
        foreach ($contactIds as $contactId) {
            $msg = \App\Models\ChMessage::where(function($q) use ($userId, $contactId) {
                    $q->where('from_id', $userId)->where('to_id', $contactId);
                })->orWhere(function($q) use ($userId, $contactId) {
                    $q->where('from_id', $contactId)->where('to_id', $userId);
                })
                ->orderByDesc('created_at')
                ->first();
            if ($msg) $latestChats->push($msg);
        }
        $latestChats = $latestChats->sortByDesc('created_at')->take(5);
        return view('dashboard_employer', [
            'employer' => $employer,
            'scoutCounts' => $scoutCounts,
            'newScouts' => $newScouts,
            'latestMessages' => $latestChats,
        ]);
    }

    public function index()
    {
        $user = Auth::user();
        $teacher = $user->teacher; // Userモデルにteacherリレーションがある前提

        // ダミー: スカウト状況
        $scoutCounts = [
            'new' => 2,
            'pending' => 1,
            'matched' => 0,
        ];
        $newScouts = [];

        // 最新のチャット（部屋単位で最新1件ずつ、最大5名分）
        $userId = $user->id;
        // 自分とやりとりした相手IDリスト
        $contactIds = \App\Models\ChMessage::where('from_id', $userId)
            ->orWhere('to_id', $userId)
            ->selectRaw('IF(from_id = ?, to_id, from_id) as contact_id', [$userId])
            ->groupBy('contact_id')
            ->pluck('contact_id');
        $latestChats = collect();
        foreach ($contactIds as $contactId) {
            $msg = \App\Models\ChMessage::where(function($q) use ($userId, $contactId) {
                    $q->where('from_id', $userId)->where('to_id', $contactId);
                })->orWhere(function($q) use ($userId, $contactId) {
                    $q->where('from_id', $contactId)->where('to_id', $userId);
                })
                ->orderByDesc('created_at')
                ->first();
            if ($msg) $latestChats->push($msg);
        }
        $latestChats = $latestChats->sortByDesc('created_at')->take(5);

        return view('dashboard', [
            'teacher' => $teacher,
            'scoutCounts' => $scoutCounts,
            'newScouts' => $newScouts,
            'latestMessages' => $latestChats,
        ]);
    }
}

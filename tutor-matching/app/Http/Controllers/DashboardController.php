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
        // 1. スカウト総数（すべてのスカウト）
        $scoutTotal = $teacher ? $teacher->scoutRequests()->count() : 0;
        // 2. 未対応スカウト（new/pending かつ講師から返信がないもの）
        $unrespondedScoutCount = 0;
        if ($teacher) {
            $scouts = $teacher->scoutRequests()->whereIn('status', ['new', 'pending'])->get();
            foreach ($scouts as $scout) {
                // チャットで講師が雇用者に返信したか確認
                $teacherUserId = $teacher->user_id;
                $employerUserId = $scout->employer ? $scout->employer->user_id : null;
                if (!$employerUserId) {
                    $unrespondedScoutCount++;
                    continue;
                }
                $teacherReply = \App\Models\ChMessage::where('from_id', $teacherUserId)
                    ->where('to_id', $employerUserId)
                    ->where('created_at', '>', $scout->created_at)
                    ->exists();
                if (!$teacherReply) {
                    $unrespondedScoutCount++;
                }
            }
        }
        // 既存のカウントも保持
        $scoutCounts = [
            'unresponded' => $unrespondedScoutCount,
            'total' => $scoutTotal,
            'matched' => $teacher ? $teacher->scoutRequests()->where('status', 'matched')->count() : 0,
        ];
        $scouts = [];
        if ($teacher) {
            // 重複（employer_id単位）を除き最新のみ取得
            $scouts = $teacher->scoutRequests()
                ->with(['employer'])
                ->orderByDesc('created_at')
                ->get()
                ->unique('employer_id')
                ->take(5)
                ->map(function($scout) {
                    return (object) [
                        'id' => $scout->id,
                        'employer_id' => $scout->employer_id,
                        'employer_name' => $scout->employer
                            ? trim(($scout->employer->last_name ?? '') . ' ' . ($scout->employer->first_name ?? ''))
                            : '塾',
                        'status' => $scout->status,
                    ];
                });
        }
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

        // チャット相手がemployerなら塾名をfull_nameとして持たせる
        $latestChats = $latestChats->map(function($chat) use ($userId) {
            $targetId = $chat->from_id == $userId ? $chat->to_id : $chat->from_id;
            $targetUser = \App\Models\User::find($targetId);
            $fullName = null;
            if ($targetUser && $targetUser->employer) {
                $fullName = trim(($targetUser->employer->last_name ?? '') . ' ' . ($targetUser->employer->first_name ?? ''));
            } elseif ($targetUser) {
                $fullName = trim(($targetUser->last_name ?? '') . ' ' . ($targetUser->first_name ?? ''));
            }
            $chat->target_full_name = $fullName ?: '相手';
            return $chat;
        });

        return view('dashboard_teacher', [
            'teacher' => $teacher,
            'scoutCounts' => $scoutCounts,
            'scouts' => $scouts,
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
        // 応募してきた講師リスト（Entryモデルから取得）
        $entries = \App\Models\Entry::where('employer_id', $employer->id)
            ->orderByDesc('created_at')
            ->take(5)
            ->with('user.teacher')
            ->get();
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
        // チャット相手がteacherなら氏名、employerなら塾名をfull_nameとして持たせる
        $latestChats = $latestChats->map(function($chat) use ($userId) {
            $targetId = $chat->from_id == $userId ? $chat->to_id : $chat->from_id;
            $targetUser = \App\Models\User::find($targetId);
            $fullName = null;
            if ($targetUser && $targetUser->teacher) {
                $fullName = trim(($targetUser->teacher->last_name ?? '') . ' ' . ($targetUser->teacher->first_name ?? ''));
            } elseif ($targetUser && $targetUser->employer) {
                $fullName = trim(($targetUser->employer->last_name ?? '') . ' ' . ($targetUser->employer->first_name ?? ''));
            } elseif ($targetUser) {
                $fullName = trim(($targetUser->last_name ?? '') . ' ' . ($targetUser->first_name ?? ''));
            }
            $chat->target_full_name = $fullName ?: '相手';
            return $chat;
        });
        return view('dashboard_employer', [
            'employer' => $employer,
            'scoutCounts' => $scoutCounts,
            'entries' => $entries,
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

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Employer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'user_type' => 'required|in:teacher,individual_employer,corporate_employer',
            // ユーザータイプに応じた追加バリデーション
            'first_name' => 'required_if:user_type,teacher|string|max:50',
            'last_name' => 'required_if:user_type,teacher|string|max:50',
            'name' => 'required_if:user_type,individual_employer,corporate_employer|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // ユーザー作成
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type,
            'status' => 'pending',
        ]);

        // ユーザータイプに応じたプロフィール作成
        if ($request->user_type === 'teacher') {
            Teacher::create([
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'subject' => $request->subject ?? '未設定',
                'grade_level' => $request->grade_level ?? '未設定',
            ]);
        } else {
            Employer::create([
                'user_id' => $user->id,
                'name' => $request->name,
            ]);
        }

        // セッションベース認証のみ（SPA用）
        return response()->json([
            'user' => $user
        ]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // 認証
        if (!Auth::guard('web')->attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'メールアドレスまたはパスワードが正しくありません'
            ], 401);
        }

        // より確実に現在のユーザーを取得
        $user = User::find(Auth::id());
        if (!$user || !$user->id) {
            return response()->json([
                'message' => 'ユーザーが見つかりません'
            ], 404);
        }
        // 最終ログイン時間を更新
        $user->last_login = now();
        $user->save();

        // セッションベース認証のみ（SPA用）
        return response()->json([
            'user' => $user
        ]);
    }

    public function user(Request $request)
    {
        $user = $request->user();
        
        // ユーザータイプに応じてプロフィール情報をロード
        if ($user->user_type === 'teacher') {
            $user->load('teacher');
        } else {
            $user->load('employer');
        }
        
        return response()->json($user);
    }

    public function me(Request $request)
    {
        $userFromRequest = $request->user();
        $userFromAuth = \Auth::guard('web')->user();
        return response()->json([
            'user_from_request' => $userFromRequest,
            'user_from_request_id' => $userFromRequest ? $userFromRequest->id : null,
            'user_from_auth' => $userFromAuth,
            'user_from_auth_id' => $userFromAuth ? $userFromAuth->id : null,
            'session_id' => session()->getId(),
            'all_session' => session()->all(),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['message' => 'ログアウトしました']);
    }
}

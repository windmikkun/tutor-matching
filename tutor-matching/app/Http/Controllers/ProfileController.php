<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        // teacher.account.editルートの場合は専用bladeを返す
        if (request()->route()->getName() === 'teacher.account.edit') {
            return view('teacher.account_edit', [
                'user' => $request->user(),
            ]);
        }
        // それ以外はデフォルトprofile.edit
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('teacher.account.edit')->with('status', 'profile-updated');
    }

    /**
     * プロフィール画像アップロードAPI
     */
    public function uploadProfileImage(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['success' => false, 'error' => '認証が必要です'], 401);
        }
        // セキュリティチェック1: ファイル存在・サイズ・拡張子
        if (!$request->hasFile('profile_image')) {
            return response()->json(['success' => false, 'error' => '画像ファイルがありません'], 400);
        }
        $file = $request->file('profile_image');
        if (!$file->isValid() || $file->getSize() > 512 * 1024) {
            return response()->json(['success' => false, 'error' => '500KB以下の有効な画像のみアップロード可能です'], 400);
        }
        $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'avif'];
        $ext = strtolower($file->getClientOriginalExtension());
        if (!in_array($ext, $allowedExts)) {
            return response()->json(['success' => false, 'error' => 'JPEG/PNG/WebP/AVIFのみ対応です。'], 400);
        }
        // セキュリティチェック2: MIMEタイプ
        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/avif'];
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            return response()->json(['success' => false, 'error' => 'JPEG/PNG/WebP/AVIFのみ対応です。'], 400);
        }
        // 画像圧縮・変換（Intervention Image）
        $manager = new ImageManager([ 'driver' => 'gd' ]);
        $image = $manager->make($file->getPathname());
        // サイズ調整（長辺1024px以内）
        $image->resize(1024, 1024, function($constraint) { $constraint->aspectRatio(); $constraint->upsize(); });
        // WebP変換＆圧縮
        $webpData = (string) $image->encode('webp', 80); // 品質80
        // AVIF変換（サーバーGD/Imagick対応時のみ）
        try {
            $avifData = (string) $image->encode('avif', 60);
        } catch (\Exception $e) {
            $avifData = null;
        }
        // 200KB以下に圧縮（WebP優先、できなければJPEG）
        $finalData = strlen($webpData) <= 200 * 1024 ? $webpData : (string) $image->encode('jpeg', 70);
        $finalExt = strlen($webpData) <= 200 * 1024 ? 'webp' : 'jpg';
        // ファイル保存
        $path = 'profile_images/user_' . $user->id . '_' . time() . '.' . $finalExt;
        Storage::disk('public')->put($path, $finalData);
        // 古い画像削除（必要なら）
        if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
            Storage::disk('public')->delete($user->profile_image);
        }
        // ユーザー情報更新
        $user->profile_image = $path;
        $user->save();
        return response()->json(['success' => true, 'path' => Storage::url($path)]);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

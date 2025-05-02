<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployerProfileController extends Controller
{
    // 閲覧専用プロフィール表示
    public function show()
    {
        $user = Auth::user();
        $employer = $user->employer ?? null;
        return view('employer_profile_show', compact('user', 'employer'));
    }
    public function edit()
    {
        $user = Auth::user();
        $employer = $user->employer ?? null;
        return view('employer.edit_profile', compact('user', 'employer'));
    }
    public function update(Request $request)
    {
        $user = Auth::user();
        $employer = $user->employer;
        $validated = $request->validate([
            'first_name' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'profile_image' => 'nullable|image|max:5120',
            'lesson_type' => 'nullable|string|max:32',
            'student_count' => 'nullable|integer|min:0',
            'student_demographics' => 'nullable|string|max:255',
            'recruiting_subject' => 'nullable|string|max:255',
            'hourly_rate' => 'nullable|integer|min:0',
            'env_img.*' => 'nullable|image|max:5120', // 最大3枚
            'phone' => 'nullable|string|max:32',
        ]);
        // プロフィール画像アップロード処理
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('employer_profiles', 'public');
            $employer->profile_image = $path;
        }
        // 教室等の画像アップロード処理
        $classroomImages = [];
        if ($request->hasFile('env_img')) {
            foreach ($request->file('env_img') as $idx => $img) {
                if ($img && $idx < 3) {
                    $path = $img->store('employer_classrooms', 'public');
                    $classroomImages[] = $path;
                }
            }
            $employer->env_img = json_encode($classroomImages);
        }
        $employer->first_name = $validated['first_name'] ?? null;
        $employer->contact_person = $validated['contact_person'] ?? null;
        $employer->description = $validated['description'] ?? null;
        $employer->lesson_type = $validated['lesson_type'] ?? null;
        $employer->student_count = $validated['student_count'] ?? null;
        $employer->student_demographics = $validated['student_demographics'] ?? null;
        $employer->recruiting_subject = $validated['recruiting_subject'] ?? null;
        $employer->hourly_rate = $validated['hourly_rate'] ?? null;
        $employer->save();
        
        // 電話番号はusersテーブルへ保存
        $user->phone = $request->input('phone');
        $user->save();
        
        return redirect()->route('employer.profile.edit')->with(['status' => 'profile-updated', 'success' => 'プロフィールを更新しました']);
    }
}

<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $teacher = $user->teacher ?? null;
        return view('teacher.edit_profile', compact('user', 'teacher'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'grade_level' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'profile_image' => 'nullable|image|max:2048',
        ]);
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('teacher_profiles', 'public');
            $teacher->profile_image = $path;
        }
        $teacher->first_name = $validated['first_name'];
        $teacher->last_name = $validated['last_name'];
        $teacher->subject = $validated['subject'] ?? null;
        $teacher->grade_level = $validated['grade_level'] ?? null;
        $teacher->bio = $validated['bio'] ?? null;
        $teacher->save();
        return redirect()->route('teacher.profile.edit')->with('success', 'プロフィールを更新しました');
    }
}

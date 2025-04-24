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
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:32',
            'address' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'profile_image' => 'nullable|image|max:2048',
        ]);
        // 画像アップロード処理
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('employer_profiles', 'public');
            $employer->profile_image = $path;
        }
        $employer->name = $validated['name'];
        $employer->contact_person = $validated['contact_person'] ?? null;
        $employer->phone = $validated['phone'] ?? null;
        $employer->address = $validated['address'] ?? null;
        $employer->description = $validated['description'] ?? null;
        $employer->save();
        return redirect()->route('employer.profile.edit')->with('success', 'プロフィールを更新しました');
    }
}

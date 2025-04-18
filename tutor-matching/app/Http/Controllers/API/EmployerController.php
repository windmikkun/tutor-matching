<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Employer;
use Illuminate\Http\Request;

class EmployerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 雇用者一覧を返す
        $employers = Employer::all();
        return response()->json($employers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 本番用: user_idはリクエストから受け取らず自動セット
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            // 必要に応じて他の項目も追加
        ]);
        $user_id = $request->user()->id;
        if (Employer::where('user_id', $user_id)->exists()) {
            return response()->json(['message' => '既に雇用者プロフィールが存在します'], 409);
        }
        $employer = Employer::create(array_merge($validated, ['user_id' => $user_id]));
        return response()->json($employer, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Employer $employer)
    {
        return response()->json($employer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employer $employer)
    {
        // 本人以外は編集不可
        if ($request->user()->id !== $employer->user_id) {
            return response()->json(['message' => '権限がありません'], 403);
        }
        $employer->update($request->all());
        return response()->json($employer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employer $employer)
    {
        // 本人以外は削除不可
        if (request()->user()->id !== $employer->user_id) {
            return response()->json(['message' => '権限がありません'], 403);
        }
        $employer->delete();
        return response()->json(['message' => 'Deleted']);
    }

    /**
     * Get the employer profile for the authenticated user.
     */
    public function me(Request $request)
    {
        $user_id = $request->user()->id;
        $employer = Employer::where('user_id', $user_id)->first();
        if (!$employer) {
            return response()->json(['message' => '雇用者プロフィールが存在しません'], 404);
        }
        return response()->json($employer);
    }
}

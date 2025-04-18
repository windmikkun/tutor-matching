<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // すべての先生（Teacher）データを取得
        $teachers = Teacher::all();

        // 取得したデータをJSON形式で返す
        return response()->json($teachers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. 入力内容のバリデーション（チェック）
        $validated = $request->validate([
            'user_id'    => 'required|exists:users,id',
            'first_name' => 'required|string|max:50',
            'last_name'  => 'required|string|max:50',
        ]);

        // 2. Teacherモデルを使って新しい先生データを作成
        $teacher = Teacher::create($validated);

        // 3. 作成したデータをJSON形式で返す（201: Created）
        return response()->json($teacher, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher)
    {
        //指定された先生（Teacher)の情報をJSON形式で返す
        return response()->json($teacher);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Teacher $teacher)
    {
        //1.入力内容のバリデーション
        $validated = $request->validate([
            'first_name' => 'sometimes|required|string|max;50',
            'last_name' => 'sometimes|required|string|max:50'
        ]);

        //2.　データを更新
        $teacher->update($validated);

        //3. 更新後のデータをJSON形式で返す
        return response()->json($teacher);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        //指定された先生データを削除
        $teacher->delete();

        //削除成功のメッセージを返す
        return response()->json(['message' => '削除しました'], 200);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;

class TeacherListController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with('user')->get();
        return view('teacher_list', compact('teachers'));
    }

    public function show($id)
    {
        $teacher = Teacher::with('user')->findOrFail($id);
        return view('teacher_show', compact('teacher'));
    }
}

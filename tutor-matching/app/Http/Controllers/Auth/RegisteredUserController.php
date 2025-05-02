<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:50'], // 担当者名
            'last_name' => ['required', 'string', 'max:50'], // 会社名・事業所名
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'user_type' => ['nullable', 'in:teacher,employer'],

        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => $request->input('user_type', 'teacher'),
        ]);

        if ($request->input('user_type') === 'employer') {
            \App\Models\Employer::create([
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
            ]);
        } elseif ($request->input('user_type', 'teacher') === 'teacher') {
            \App\Models\Teacher::create([
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
            ]);
        }

        event(new Registered($user));
        Auth::login($user);
        // ユーザー種別でダッシュボードにリダイレクト
        if ($user->user_type === 'employer') {
            return redirect('/teachers');
        } elseif ($user->user_type === 'teacher') {
            return redirect('/jobs');
        }
        return redirect(RouteServiceProvider::HOME);
    }
}

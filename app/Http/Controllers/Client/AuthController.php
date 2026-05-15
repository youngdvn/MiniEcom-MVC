<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (auth()->check()) {
            return redirect()->route('home');
        }

        return view('client.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate(
            [
                'username' => 'required',
                'password' => 'required',
            ],
            [
                'required' => ':attribute không được để trống',
            ],
            [
                'username' => 'Tên đăng nhập hoặc email',
                'password' => 'Mật khẩu',
            ]
        );

        $key = trim((string) $request->username);
        $user = User::where('username', $key)->orWhere('email', $key)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with('message', 'Thông tin đăng nhập không đúng')->withInput();
        }

        Auth::login($user, $request->boolean('remember'));

        return redirect()->intended(route('home'));
    }

    public function showRegister()
    {
        if (auth()->check()) {
            return redirect()->route('home');
        }

        return view('client.auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate(
            [
                'username' => 'required|string|max:50|unique:users,username',
                'fullname' => 'required|string|max:100|unique:users,fullname',
                'email' => 'required|email|max:150|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
            ],
            [],
            [
                'username' => 'Tên đăng nhập',
                'fullname' => 'Họ và tên',
                'email' => 'Email',
                'password' => 'Mật khẩu',
            ]
        );

        $user = User::create([
            'username' => $data['username'],
            'fullname' => $data['fullname'],
            'email' => $data['email'],
            'role' => 0,
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('message', 'Đăng ký tài khoản thành công');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}

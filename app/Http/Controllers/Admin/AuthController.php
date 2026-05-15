<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    // xử lý đăng nhập
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
                'username' => 'Tên đăng nhập',
                'password' => 'Mật khẩu',
            ]
        );

        $data = trim((string) $request->username);
        $user = User::where('username', $data)
            ->orWhere('email', $data)
            ->first();

        if (!$user) {
            return back()
                ->with('message', 'Tên đăng nhập hoặc email không tồn tại')
                ->withInput();
        }

        if ((int) $user->role !== 1) {
            return back()
                ->with('message', 'Tài khoản này không có quyền đăng nhập quản trị')
                ->withInput();
        }

        $check = Hash::check($request->password, $user->password); // true hoặc false

        if (!$check) {
            return back()
                ->with('message', 'Mật khẩu không đúng')
                ->withInput();
        }


        $remember = $request->has('remember') ? true : false;
        Auth::login($user, $remember);
        return redirect()->intended(route('admin.dashboard'));
    }

    public function showLogin()
    {
        if (auth()->check()) {
            if ((int) Auth::user()->role === 1) {
                return redirect()->intended('/admin/dashboard');
            }

            return redirect()->route('home');
        }

        return view('admin.login');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if($user)
            {
                User::where('id', $user->id)->update([
                    'remember_token'=>null
                ]);
            }
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    public function showForgot()
    {
        return view('admin.forgot');
    }

    public function forgot(Request $request)
    {
        // validate - kiểm tra dữ liệu đầu vào
        $request->validate(
            ['email' => 'required|email'],
            [
                'email.required' => 'Email không được để trống',
                'email.email' => 'Email không đúng định dạng',
            ]
        );

        // Kiểm tra email tồn tại
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return redirect()->route('admin.forgot')
                ->with('message', 'Email không tồn tại')
                ->withInput();
        }

        // Tạo mật khẩu mới
        $passrandom = Str::random(10);

        // Mã hóa mật khẩu
        $passencrypted = Hash::make($passrandom);

        // Lưu vào DB
        $user->update([
            'password' => $passencrypted
        ]);

        // Nội dung email
        $html = "<h2>Mật khẩu mới của bạn là: $passrandom</h2>
        <p>Vui lòng đổi mật khẩu sau khi đăng nhập.</p>";

        // Gửi email
        Mail::html($html, function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Đặt lại mật khẩu');
        });

        // điều hướng về rang forgot kèm thông báo
        return redirect()->route('admin.forgot')
            ->with('message', 'Đã gửi mật khẩu mới. Bạn vui lòng kiểm tra email của bạn');
    }

}


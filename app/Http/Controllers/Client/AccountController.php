<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('client.account.edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'fullname' => 'required|string|max:100|unique:users,fullname,' . $user->id,
            'email' => 'required|email|max:150|unique:users,email,' . $user->id,
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
        ]);

        $user->update($data);

        return back()->with('message', 'Cập nhật thông tin thành công');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('message', 'Mật khẩu hiện tại không đúng');
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('message', 'Đổi mật khẩu thành công');
    }
}

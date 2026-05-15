<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserPasswordRequest;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Throwable;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim((string) $request->keyword);
        $role = (string) $request->role;
        $limit = (int) ($request->limit ?? 10);
        if (!in_array($limit, [5, 10, 20, 50], true)) {
            $limit = 10;
        }

        $list = User::query()
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where('username', 'like', "%{$keyword}%")
                    ->orWhere('fullname', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%");
            })
            ->when($role !== '' && in_array($role, ['0', '1'], true), function ($query) use ($role) {
                $query->where('role', (int) $role);
            })
            ->orderByDesc('id')
            ->paginate($limit)
            ->withQueryString();

        $stats = [
            'total' => User::count(),
            'admin' => User::where('role', 1)->count(),
            'client' => User::where('role', 0)->count(),
        ];

        return view('admin.user.index', compact('list', 'keyword', 'role', 'limit', 'stats'));
    }
    public function create()
    {
        return view('admin.user.create');
    }

    public function store(UserRequest $request)
    {
        try {
            $data = $request->validated();

            User::create([
                'username' => $data['username'],
                'fullname' => $data['fullname'],
                'email' => $data['email'],
                'role' => $data['role'],
                'password' => Hash::make('123456'),
            ]);

            return redirect()->route('admin.user.index')->with('message', 'Thêm tài khoản thành công');
        } catch (Throwable $e) {
            return back()
                ->withErrors(['general' => 'Thêm tài khoản thất bại'])
                ->withInput();
        }
    }
    public function edit($id)
    {
        $model = User::find($id);
        return view('admin.user.edit', compact('model'));
    }

    public function update(UserRequest $request, $id)
    {
        try {
            $data = $request->validated();

            $model = User::find($id);
            $model->update([
                'username' => $data['username'],
                'fullname' => $data['fullname'],
                'email' => $data['email'],
                'role' => $data['role'],
            ]);
            return redirect()->route('admin.user.index')->with('message', 'Cập nhật tài khoản thành công');
        } catch (Throwable $e) {
            return back()
                ->withErrors(['general' => 'Cập nhật tài khoản thất bại'])
                ->withInput();
        }
    }

    public function destroy($id)
    {
        User::destroy($id);
        return redirect()->route('admin.user.index')->with('message', 'Xóa tài khoản thành công');
    }

    public function editPassword($id)
    {
        $model = User::find($id);
        return view('admin.user.password', compact('model'));
    }

    public function updatePassword(UserPasswordRequest $request, $id)
    {
        try {
            $data = $request->validated();

            $model = User::find($id);
            $model->update([
                'password' => Hash::make($data['password']),
                'remember_token' => null,
            ]);

            return redirect()->route('admin.user.index')->with('message', 'Đổi mật khẩu thành công');
        } catch (Throwable $e) {
            return back()
                ->withErrors(['general' => 'Đổi mật khẩu thất bại'])
                ->withInput();
        }
    }
}

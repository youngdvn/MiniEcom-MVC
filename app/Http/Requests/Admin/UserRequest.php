<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'username' => 'required|min:3|max:50',
            'fullname' => 'required|min:3|max:100|unique:users,fullname,' . $id . ',id',
            'email' => 'required|email|max:255|unique:users,email,' . $id . ',id',
            'role' => 'required|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Tên người dùng không được để trống',
            'username.min' => 'Tên người dùng phải có ít nhất :min ký tự',
            'username.max' => 'Tên người dùng tối đa :max ký tự',

            'fullname.required' => 'Họ và tên không được để trống',
            'fullname.min' => 'Họ và tên phải có ít nhất :min ký tự',
            'fullname.max' => 'Họ và tên tối đa :max ký tự',
            'fullname.unique' => 'Họ và tên đã tồn tại',

            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không đúng định dạng',
            'email.max' => 'Email tối đa :max ký tự',
            'email.unique' => 'Email đã tồn tại',

            'role.required' => 'Vai trò không được để trống',
            'role.in' => 'Vai trò không hợp lệ',
        ];
    }
}

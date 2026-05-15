<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'title' => 'required|min:10|max:255',
            'slug' => 'required|min:10|max:50|regex:/^[a-zA-Z0-9-]+$/|unique:posts,slug,' . $id,
            'userid' => 'required|integer|exists:users,id',
            'image' => 'nullable|string|max:255',
            'content' => 'required',
            'status' => 'required|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Tiêu đề không được để trống',
            'title.min' => 'Tiêu đề phải có ít nhất :min ký tự',
            'title.max' => 'Tiêu đề tối đa :max ký tự',

            'slug.required' => 'Slug không được để trống',
            'slug.min' => 'Slug phải có ít nhất :min ký tự',
            'slug.max' => 'Slug tối đa :max ký tự',
            'slug.regex' => 'Slug chỉ chứa chữ, số và dấu gạch ngang (-)',
            'slug.unique' => 'Slug đã tồn tại',

            'userid.required' => 'Tác giả không được để trống',
            'userid.integer' => 'Tác giả không hợp lệ',
            'userid.exists' => 'Tác giả không tồn tại',

            'image.string' => 'Ảnh phải là đường dẫn hoặc URL hợp lệ',
            'image.max' => 'Đường dẫn ảnh tối đa :max ký tự',

            'content.required' => 'Nội dung không được để trống',

            'status.required' => 'Trạng thái không được để trống',
            'status.in' => 'Trạng thái không hợp lệ',
        ];
    }
}

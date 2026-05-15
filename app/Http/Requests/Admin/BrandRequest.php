<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BrandRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
public function rules(): array
{
    // Lấy giá trị id từ URL (route parameter)
    // Trường hợp Insert - không có id => $id = null
    // Trường hợp Update - có id
    $id = $this->route('id');

    return [
        'brandname' => 'required|min:3|max:20|unique:brands,brandname,' . $id,
        'slug' => 'required|min:3|max:20|regex:/^[a-zA-Z0-9-]+$/|unique:brands,slug,' . $id,
        'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:200',
        'status' => 'nullable|boolean',
    ];
}
public function messages(): array
{
    return [
        'required' => ':attribute không được để trống',
        'unique' => ':attribute đã tồn tại',

        'brandname.min' => ':attribute phải có ít nhất :min ký tự',
        'brandname.max' => ':attribute tối đa :max ký tự',
        'slug.min' => ':attribute phải có ít nhất :min ký tự',
        'slug.max' => ':attribute tối đa :max ký tự',
        'slug.regex' => ':attribute chỉ chứa chữ, số và dấu gạch ngang (-)',
        'thumbnail.image' => 'Ảnh đại diện phải là file ảnh',
        'thumbnail.mimes' => 'Ảnh đại diện phải có định dạng: jpg, jpeg, png',
        'thumbnail.max' => 'Ảnh đại diện không được vượt quá 200KB',
    ];
}
public function attributes(): array
{
    return [
        'brandname' => 'Tên thương hiệu',
        'slug' => 'Slug',
    ];
}
}

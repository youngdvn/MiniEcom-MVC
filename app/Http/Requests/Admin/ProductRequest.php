<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'proname' => 'required|min:10|max:30|not_regex:/[$#@]/|unique:products,proname,' . $id,
            'slug' => 'required|min:10|max:30|regex:/^[a-zA-Z0-9-]+$/|unique:products,slug,' . $id,
            'price' => 'required|numeric|between:10000,300000000',
            'sale_price' => 'nullable|numeric|between:1000,300000000|lt:price',
            'stock_quantity' => 'required|integer|min:0|max:1000000',
            'variants' => 'nullable|array',
            'variants.*.id' => 'nullable|integer|exists:product_variants,id',
            'variants.*.size' => 'nullable|string|max:20',
            'variants.*.price' => 'nullable|numeric|min:1|max:3000000000',
            'variants.*.sale_price' => 'nullable|numeric|min:1|max:3000000000',
            'variants.*.stock_quantity' => 'nullable|integer|min:0|max:1000000',
            'variants.*.status' => 'nullable|in:0,1',
            'cateid' => 'required|integer|exists:categories,cateid',
            'brandid' => 'nullable|integer|exists:brands,id',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:200',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:200',
        ];
    }

    public function messages(): array
    {
        return [
            'proname.required' => 'Tên sản phẩm không được để trống',
            'proname.min' => 'Tên sản phẩm phải từ :min ký tự',
            'proname.max' => 'Tên sản phẩm tối đa :max ký tự',
            'proname.unique' => 'Tên sản phẩm đã tồn tại',
            'proname.not_regex' => 'Tên sản phẩm không được chứa ký tự $, #, @',

            'slug.required' => 'Slug không được để trống',
            'slug.min' => 'Slug phải từ :min ký tự',
            'slug.max' => 'Slug tối đa :max ký tự',
            'slug.regex' => 'Slug chỉ chứa chữ, số và dấu gạch ngang (-)',
            'slug.unique' => 'Slug đã tồn tại',

            'price.required' => 'Giá không được để trống',
            'price.numeric' => 'Giá phải là số',
            'price.between' => 'Giá phải trong khoảng 10000 đến 300,000,000',
            'sale_price.numeric' => 'Giá khuyến mãi phải là số',
            'sale_price.between' => 'Giá khuyến mãi phải trong khoảng 1000 đến 300,000,000',
            'sale_price.lt' => 'Giá khuyến mãi phải nhỏ hơn giá gốc',
            'stock_quantity.required' => 'Số lượng tồn kho không được để trống',
            'stock_quantity.integer' => 'Số lượng tồn kho phải là số nguyên',
            'stock_quantity.min' => 'Số lượng tồn kho tối thiểu là 0',
            'stock_quantity.max' => 'Số lượng tồn kho quá lớn',
            'variants.*.size.max' => 'Tên phiên bản tối đa 20 ký tự',
            'variants.*.price.numeric' => 'Giá biến thể phải là số',
            'variants.*.sale_price.numeric' => 'Giá khuyến mãi biến thể phải là số',
            'variants.*.stock_quantity.integer' => 'Tồn kho biến thể phải là số nguyên',

            'cateid.required' => 'Loại sản phẩm không được để trống',
            'cateid.integer' => 'Loại sản phẩm không hợp lệ',
            'cateid.exists' => 'Loại sản phẩm không tồn tại',

            'brandid.integer' => 'Thương hiệu không hợp lệ',
            'brandid.exists' => 'Thương hiệu không tồn tại',

            'thumbnail.image' => 'Ảnh đại diện phải là file ảnh',
            'thumbnail.mimes' => 'Ảnh đại diện phải có định dạng: jpg, jpeg, png',
            'thumbnail.max' => 'Ảnh đại diện không được vượt quá 200KB',

            'images.*.image' => 'Ảnh liên quan phải là file ảnh',
            'images.*.mimes' => 'Ảnh liên quan phải có định dạng: jpg, jpeg, png',
            'images.*.max' => 'Ảnh liên quan không được vượt quá 200KB',
        ];
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Throwable;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim((string) $request->keyword);
        $status = (string) $request->status;
        $limit = (int) ($request->limit ?? 10);
        if (!in_array($limit, [5, 10, 20, 50], true)) {
            $limit = 10;
        }

        $list = Category::select('cateid', 'catename', 'slug', 'thumbnail', 'status', 'description', 'created_at')
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where('catename', 'like', "%{$keyword}%")
                    ->orWhere('slug', 'like', "%{$keyword}%");
            })
            ->when($status !== '' && in_array($status, ['0', '1'], true), function ($query) use ($status) {
                $query->where('status', (int) $status);
            })
            ->orderByDesc('cateid')
            ->paginate($limit)
            ->withQueryString();

        $stats = [
            'total' => Category::count(),
            'active' => Category::where('status', 1)->count(),
            'hidden' => Category::where('status', 0)->count(),
        ];

        return view('admin.category.index', compact('list', 'limit', 'keyword', 'status', 'stats'));
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'catename' => 'required|min:5|max:50|unique:categories,catename',
            'slug' => 'required|min:5|max:50|unique:categories,slug|regex:/^[a-zA-Z0-9-]+$/',
            'thumbnail'=> 'nullable|image|mimes:jpg,jpeg,png|max:100'
        ], [
            'catename.required' => 'Tên loại sản phẩm không được để trống',
            'catename.min' => 'Tên phải có độ dài tối thiểu :min ký tự',
            'catename.max' => 'Tên phải có độ dài tối đa :max ký tự',
            'catename.unique' => 'Tên đã tồn tại',

            'slug.required' => 'Slug không được để trống',
            'slug.min' => 'Slug phải có độ dài tối thiểu :min ký tự',
            'slug.max' => 'Slug phải có độ dài tối đa :max ký tự',
            'slug.unique' => 'Slug đã tồn tại',
            'slug.regex' => 'Slug chỉ gồm chữ, số và dấu gạch ngang (-)'
        ]);

        try {


            $filename = 'default.png';
            if($request->hasFile('thumbnail'))
                {
                    $file = $request->file('thumbnail');
                    $filename = uniqid() . '_' . Str::slug($request->catename) . '.' . $file->extension();
                    $file->storeAs('categories', $filename, 'public');
                }

            // Lưu dữ liệu xuống database
            Category::create([
                'catename' => $request->catename,
                'slug' => $request->slug,
                'description' => $request->description,
                'thumbnail' => $filename,
                'status' => $request->has('status') ? 1 : 0,
            ]);

            // điều hướng về trang index
                return redirect()->route('admin.cate.index')->with('message', 'Thêm danh mục thành công');

        } catch (Throwable $e) {
            // Trường hợp thực hiện lưu thất bại
            return back()
                ->withErrors(['general' => 'Thêm danh mục thất bại'])
                ->withInput();
        }
    }
    public function edit($id)
    {
        $model = Category::find($id);
        return view('admin.category.edit', compact('model'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'catename' => 'required|min:5|max:50|unique:categories,catename,' . $id . ',cateid',
            'slug' => 'required|min:5|max:50|regex:/^[a-zA-Z0-9-]+$/|unique:categories,slug,' . $id . ',cateid',
            'thumbnail'=> 'nullable|image|mimes:jpg,jpeg,png|max:100'
        ], [
            'catename.required' => 'Tên loại sản phẩm không được để trống',
            'catename.min' => 'Tên phải có độ dài tối thiểu :min ký tự',
            'catename.max' => 'Tên phải có độ dài tối đa :max ký tự',
            'catename.unique' => 'Tên đã tồn tại',

            'slug.required' => 'Slug không được để trống',
            'slug.min' => 'Slug phải có độ dài tối thiểu :min ký tự',
            'slug.max' => 'Slug phải có độ dài tối đa :max ký tự',
            'slug.unique' => 'Slug đã tồn tại',
            'slug.regex' => 'Slug chỉ gồm chữ, số và dấu gạch ngang (-)'
        ]);
    try {
        // TH1: tìm dữ liệu theo khóa chính
        $model = Category::find($id);

        $filename = $model->thumbnail;
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = uniqid() . '_' . Str::slug($request->catename) . '.' . $file->extension();
            $file->storeAs('categories', $filename, 'public');
        }

        // TH2: tìm dữ liệu
        // $model = Category::where('cateid', $id);
        // --------------------

        // TH1: nếu Tên input trùng với tên column trong bảng
        // $model->update($request->all());

        // TH2: nếu không trùng tên
        $model->update([
            'catename' => $request->catename,
            'slug' => $request->slug,
            'description' => $request->description,
            'thumbnail' => $filename,
            'status' => $request->has('status') ? 1 : 0,
        ]);

            return redirect()->route('admin.cate.index')->with('message', 'Cập nhật danh mục thành công');

        } catch (Throwable $e) {
            // Trường hợp thực hiện lưu thất bại
            return back()
                ->withErrors(['general' => 'Cập nhật danh mục thất bại'])
                ->withInput();
        }
    }

    public function destroy($id)
    {
        Category::destroy($id);
        return redirect()->route('admin.cate.index')->with('message', 'Xóa danh mục thành công');
    }
}

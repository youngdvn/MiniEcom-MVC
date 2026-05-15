<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BrandRequest;
use App\Models\Brand;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Throwable;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim((string) $request->keyword);
        $status = (string) $request->status;
        $limit = (int) ($request->limit ?? 10);
        if (!in_array($limit, [5, 10, 20, 50], true)) {
            $limit = 10;
        }

        $list = Brand::query()
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where('brandname', 'like', "%{$keyword}%")
                    ->orWhere('slug', 'like', "%{$keyword}%");
            })
            ->when($status !== '' && in_array($status, ['0', '1'], true), function ($query) use ($status) {
                $query->where('status', (int) $status);
            })
            ->orderByDesc('id')
            ->paginate($limit)
            ->withQueryString();

        $stats = [
            'total' => Brand::count(),
            'active' => Brand::where('status', 1)->count(),
            'hidden' => Brand::where('status', 0)->count(),
        ];

        return view('admin.brand.index', compact('list', 'limit', 'keyword', 'status', 'stats'));
    }
    public function create()
    {
        return view('admin.brand.create');
    }

    public function store(BrandRequest $request)
    {
        try {
            $data = $request->validated();
            $filename = 'default.png';
            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $filename = uniqid() . '_' . Str::slug($data['brandname']) . '.' . $file->extension();
                $file->storeAs('brands', $filename, 'public');
            }

            Brand::create([
                'brandname' => $data['brandname'],
                'slug' => $data['slug'],
                'description' => $request->description,
                'thumbnail' => $filename,
                'status' => $request->has('status') ? 1 : 0,
            ]);

            return redirect()->route('admin.brand.index')->with('message', 'Thêm thương hiệu thành công');
        } catch (Throwable $e) {
            return back()
                ->withErrors(['general' => 'Thêm thương hiệu thất bại'])
                ->withInput();
        }
    }
    public function edit($id)
    {
        $model = Brand::find($id);
        return view('admin.brand.edit', compact('model'));
    }

    public function update(BrandRequest $request, $id)
    {
        try {
            $data = $request->validated();

            $model = Brand::find($id);
            $filename = $model->thumbnail;
            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $filename = uniqid() . '_' . Str::slug($data['brandname']) . '.' . $file->extension();
                $file->storeAs('brands', $filename, 'public');
            }

            $model->update([
                'brandname' => $data['brandname'],
                'slug' => $data['slug'],
                'description' => $request->description,
                'thumbnail' => $filename,
                'status' => $request->has('status') ? 1 : 0,
            ]);
            return redirect()->route('admin.brand.index')->with('message', 'Cập nhật thương hiệu thành công');
        } catch (Throwable $e) {
            return back()
                ->withErrors(['general' => 'Cập nhật thương hiệu thất bại'])
                ->withInput();
        }
    }

    public function destroy($id)
    {
        Brand::destroy($id);
        return redirect()->route('admin.brand.index')->with('message', 'Xóa thương hiệu thành công');
    }
}

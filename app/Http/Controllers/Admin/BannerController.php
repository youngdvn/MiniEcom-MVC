<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim((string) $request->keyword);
        $status = (string) $request->status;
        $limit = (int) ($request->limit ?? 10);
        if (!in_array($limit, [5, 10, 20, 50], true)) {
            $limit = 10;
        }

        $list = Banner::query()
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where('title', 'like', "%{$keyword}%")
                    ->orWhere('subtitle', 'like', "%{$keyword}%");
            })
            ->when($status !== '' && in_array($status, ['0', '1'], true), function ($query) use ($status) {
                $query->where('status', (int) $status);
            })
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate($limit)
            ->withQueryString();

        $stats = [
            'total' => Banner::count(),
            'active' => Banner::where('status', 1)->count(),
            'hidden' => Banner::where('status', 0)->count(),
        ];

        return view('admin.banner.index', compact('list', 'keyword', 'status', 'limit', 'stats'));
    }

    public function create()
    {
        return view('admin.banner.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'string', 'max:255', 'required_without:image_file'],
            'image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048', 'required_without:image'],
            'link' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'boolean'],
        ]);

        $imagePath = $data['image'] ?? null;
        if ($request->hasFile('image_file')) {
            $storedPath = $request->file('image_file')->store('banners', 'public');
            $imagePath = Storage::url($storedPath);
        }

        Banner::create([
            ...$data,
            'image' => $imagePath,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.banner.index')->with('message', 'Đã thêm banner.');
    }

    public function edit($id)
    {
        $model = Banner::findOrFail($id);

        return view('admin.banner.edit', compact('model'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'string', 'max:255'],
            'image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'],
            'link' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'boolean'],
        ]);

        $model = Banner::findOrFail($id);
        $imagePath = $data['image'] ?: $model->image;
        if ($request->hasFile('image_file')) {
            $storedPath = $request->file('image_file')->store('banners', 'public');
            $imagePath = Storage::url($storedPath);
        }

        $model->update([
            ...$data,
            'image' => $imagePath,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.banner.index')->with('message', 'Đã cập nhật banner.');
    }

    public function destroy($id)
    {
        Banner::destroy($id);

        return redirect()->route('admin.banner.index')->with('message', 'Đã xóa banner.');
    }
}

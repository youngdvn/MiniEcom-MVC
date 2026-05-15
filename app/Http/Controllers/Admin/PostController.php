<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostRequest;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Throwable;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim((string) $request->keyword);
        $status = (string) $request->status;
        $userid = $request->userid !== null && $request->userid !== '' ? (int) $request->userid : null;
        $limit = (int) ($request->limit ?? 10);
        if (!in_array($limit, [5, 10, 20, 50], true)) {
            $limit = 10;
        }

        $list = Post::with('user:id,username')
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where('title', 'like', "%{$keyword}%")
                    ->orWhere('slug', 'like', "%{$keyword}%");
            })
            ->when($status !== '' && in_array($status, ['0', '1'], true), function ($query) use ($status) {
                $query->where('status', (int) $status);
            })
            ->when(!is_null($userid), function ($query) use ($userid) {
                $query->where('userid', $userid);
            })
            ->orderByDesc('id')
            ->paginate($limit)
            ->withQueryString();

        $stats = [
            'total' => Post::count(),
            'published' => Post::where('status', 1)->count(),
            'hidden' => Post::where('status', 0)->count(),
        ];

        $users = User::select('id', 'username')->orderBy('username')->get();

        return view('admin.post.index', compact('list', 'keyword', 'status', 'userid', 'limit', 'stats', 'users'));
    }

    public function create()
    {
        $users = User::select('id', 'username')->orderBy('username')->get();
        return view('admin.post.create', compact('users'));
    }

    public function store(PostRequest $request)
    {
        try {
            $data = $request->validated();

            Post::create([
                'title' => $data['title'],
                'slug' => $data['slug'],
                'userid' => $data['userid'],
                'image' => $data['image'] ?? null,
                'content' => $data['content'],
                'status' => $data['status'],
            ]);

            return redirect()->route('admin.post.index')->with('message', 'Thêm bài viết thành công');
        } catch (Throwable $e) {
            return back()
                ->withErrors(['general' => 'Thêm bài viết thất bại'])
                ->withInput();
        }
    }

    public function edit($id)
    {
        $model = Post::find($id);
        $users = User::select('id', 'username')->orderBy('username')->get();
        return view('admin.post.edit', compact('model', 'users'));
    }

    public function update(PostRequest $request, $id)
    {
        try {
            $data = $request->validated();

            $model = Post::findOrFail($id);
            $model->update([
                'title' => $data['title'],
                'slug' => $data['slug'],
                'userid' => $data['userid'],
                'image' => $data['image'] ?? $model->image,
                'content' => $data['content'],
                'status' => $data['status'],
            ]);

            return redirect()->route('admin.post.index')->with('message', 'Cập nhật bài viết thành công');
        } catch (Throwable $e) {
            return back()
                ->withErrors(['general' => 'Cập nhật bài viết thất bại'])
                ->withInput();
        }
    }

    public function destroy($id)
    {
        Post::destroy($id);
        return redirect()->route('admin.post.index')->with('message', 'Xóa bài viết thành công');
    }
}


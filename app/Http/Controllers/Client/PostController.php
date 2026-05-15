<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::where('status', 1)
            ->latest()
            ->paginate(9);

        return view('client.post.index', compact('posts'));
    }

    public function show(string $slug)
    {
        $post = Post::where('status', 1)
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedPosts = Post::where('status', 1)
            ->where('id', '!=', $post->id)
            ->latest()
            ->take(4)
            ->get();

        return view('client.post.show', compact('post', 'relatedPosts'));
    }
}

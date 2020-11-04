<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostsRequest;
use App\Models\Post;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function create()
    {
        return view('posts.create');
    }

    public function store(PostsRequest $request)
    {
        $post = auth()->user()->posts()->create($request->all());

        return redirect($post->path());
    }

    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }
}
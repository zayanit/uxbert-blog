@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Update Post</div>

                <div class="card-body">
                    <form action="{{ route('updatePost', $post->id) }}" method="post">
                        @csrf
                        @method('patch')
                        <div class="form-group">
                            <label for="title">Post Title</label>
                            <input type="text" class="form-control" name="title" id="title" placeholder="post title" value="{{ $post->title }}">
                        </div>

                        <div class="form-group">
                            <label for="content">Post Content</label>
                            <textarea class="form-control" id="content" name="content" rows="8">{{ $post->content }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary float-right">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
